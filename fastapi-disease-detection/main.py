from fastapi import FastAPI, File, UploadFile
from fastapi.responses import JSONResponse
import os
import shutil
import numpy as np
import tensorflow as tf
from tensorflow.keras.preprocessing import image
from tensorflow.keras.applications.vgg19 import preprocess_input

app = FastAPI()

# Define a custom loss function if needed
class CustomCategoricalCrossentropy(tf.keras.losses.CategoricalCrossentropy):
    def __init__(self, from_logits=False, label_smoothing=0.0, axis=-1, **kwargs):
        # Ensure that the reduction is valid
        if 'reduction' in kwargs and kwargs['reduction'] == 'auto':
            kwargs['reduction'] = tf.keras.losses.Reduction.SUM_OVER_BATCH_SIZE  # Use a valid reduction method
        super().__init__(from_logits=from_logits, label_smoothing=label_smoothing, axis=axis, **kwargs)

    @classmethod
    def from_config(cls, config):
        # Remove 'fn' from config if it exists to prevent errors
        config.pop('fn', None)
        # Modify reduction to a valid value if it's set to 'auto'
        if config.get('reduction') == 'auto':
            config['reduction'] = tf.keras.losses.Reduction.SUM_OVER_BATCH_SIZE  # Set a valid reduction method
        return cls(**config)

# Custom objects dictionary
custom_objects = {
    'CategoricalCrossentropy': CustomCategoricalCrossentropy
}

# Load models with custom objects
model1 = tf.keras.models.load_model('01_cnn_model.h5', custom_objects=custom_objects)  # Adjust the path to your model
model2 = tf.keras.models.load_model('02_CNN_VGG19.h5', custom_objects=custom_objects)  # Adjust the path to your model

@app.post("/predict/")
async def predict(file: UploadFile = File(...)):
    try:
        # Save the uploaded file
        file_location = f"temp/{file.filename}"
        os.makedirs(os.path.dirname(file_location), exist_ok=True)
        with open(file_location, "wb+") as file_object:
            shutil.copyfileobj(file.file, file_object)

        # Load the image and preprocess it
        img = image.load_img(file_location, target_size=(224, 224))  # Adjust size to your model's input
        img_array = image.img_to_array(img)
        img_array = np.expand_dims(img_array, axis=0)  # Add batch dimension
        img_array = preprocess_input(img_array)  # Preprocess input for VGG19

        # Make predictions using both models
        prediction1 = model1.predict(img_array)
        prediction2 = model2.predict(img_array)

        # Average the predictions from both models
        final_prediction = (prediction1 + prediction2) / 2

        # Get the predicted class and accuracy (confidence) for each model
        predicted_class1 = np.argmax(prediction1, axis=1)[0]
        predicted_class2 = np.argmax(prediction2, axis=1)[0]
        accuracy1 = np.max(prediction1, axis=1)[0]  # Highest probability for model1
        accuracy2 = np.max(prediction2, axis=1)[0]  # Highest probability for model2
        final_predicted_class = np.argmax(final_prediction, axis=1)[0]  # Final predicted class
        final_accuracy = np.max(final_prediction, axis=1)[0]  # Average accuracy

        # Clean up temporary files
        os.remove(file_location)

        # Define class names
        class_names = ['Tomato___Bacterial_spot', 'Tomato___Early_blight', 'Tomato___Late_blight', 'Tomato___Leaf_Mold', 'Tomato___Septoria_leaf_spot', 'Tomato___Spider_mites Two-spotted_spider_mite', 'Tomato___Target_Spot', 'Tomato___Tomato_Yellow_Leaf_Curl_Virus', 'Tomato___Tomato_mosaic_virus', 'Tomato___healthy']
        predicted_disease = class_names[final_predicted_class]

        # Return JSON response with additional information
        return JSONResponse(content={
            "model1": {
                "name": "01_cnn_model.h5",
                "predicted_class": class_names[predicted_class1],
                "accuracy": round(float(accuracy1) * 100, 2)  # convert to percentage
            },
            "model2": {
                "name": "02_CNN_VGG19.h5",
                "predicted_class": class_names[predicted_class2],
                "accuracy": round(float(accuracy2) * 100, 2)  # convert to percentage
            },
            "final_prediction": {
                "predicted_disease": predicted_disease,
                "accuracy": round(float(final_accuracy) * 100, 2)  # convert to percentage
            }
        })
    except Exception as e:
        return JSONResponse(content={"error": str(e)}, status_code=500)