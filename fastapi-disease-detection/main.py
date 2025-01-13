from fastapi import FastAPI, File, UploadFile
from fastapi.responses import JSONResponse
import os
import shutil
import numpy as np
import tensorflow as tf
from tensorflow.keras.preprocessing import image
from tensorflow.keras.preprocessing.image import ImageDataGenerator
from tensorflow.keras.applications.vgg19 import preprocess_input as vgg_preprocess
from tensorflow.keras.applications.inception_v3 import preprocess_input as inception_preprocess

app = FastAPI()

# Load models
model1 = tf.keras.models.load_model('tomato_disease.h5')
model2 = tf.keras.models.load_model('02_CNN_VGG.h5')
model3 = tf.keras.models.load_model('03_CNN_inception.h5')
model4 = tf.keras.models.load_model('tomato.h5')

@app.post("/predict/")
async def predict(file: UploadFile = File(...)):
    try:
        # Save the uploaded file
        file_location = f"temp/{file.filename}"
        os.makedirs(os.path.dirname(file_location), exist_ok=True)
        with open(file_location, "wb+") as file_object:
            shutil.copyfileobj(file.file, file_object)

        # Load the image
        img = image.load_img(file_location, target_size=(256, 256))

        # Preprocess images for each model
        img_vgg = img.resize((224, 224))  # For VGG-based models and model1
        img_inception = img.resize((224, 224))  # For Inception
        img_tomato = img.resize((224, 224))  # For tomato.h5

        # Convert images to arrays
        img_array_model1 = np.expand_dims(image.img_to_array(img), axis=0) / 255.0  # Rescale for model1
        img_array_vgg = np.expand_dims(image.img_to_array(img_vgg), axis=0)
        img_array_inception = np.expand_dims(image.img_to_array(img_inception), axis=0)
        img_array_tomato = np.expand_dims(image.img_to_array(img_tomato), axis=0)

        # Preprocess arrays
        img_array_vgg = vgg_preprocess(img_array_vgg)
        img_array_inception = inception_preprocess(img_array_inception)

        # Predict using each model
        prediction1 = model1.predict(img_array_model1)
        prediction2 = model2.predict(img_array_vgg)  # VGG model
        prediction3 = model3.predict(img_array_inception)  # Inception model
        prediction4 = model4.predict(img_array_tomato)  # tomato.h5 model

        # Log predictions for debugging
        print("Prediction1 (Model1):", prediction1)
        print("Prediction2 (Model2 - VGG):", prediction2)
        print("Prediction3 (Model3 - Inception):", prediction3)
        print("Prediction4 (Model4 - tomato.h5):", prediction4)

        # Define weights for each model
        weight1 = 0.25
        weight2 = 0.25
        weight3 = 0.25
        weight4 = 0.25

        # Compute the weighted average of predictions
        final_prediction = (
            weight1 * prediction1 +
            weight2 * prediction2 +
            weight3 * prediction3 +
            weight4 * prediction4
        )

        # Get the predicted class and accuracy for the final prediction
        final_predicted_class = np.argmax(final_prediction, axis=1)[0]
        final_accuracy = np.max(final_prediction, axis=1)[0]

        # Define class names
        class_names = [
            'Tomato___Bacterial_spot',
            'Tomato___Early_blight',
            'Tomato___Late_blight',
            'Tomato___Leaf_Mold',
            'Tomato___Septoria_leaf_spot',
            'Tomato___Spider_mites Two-spotted_spider_mite',
            'Tomato___Target_Spot',
            'Tomato___Tomato_Yellow_Leaf_Curl_Virus',
            'Tomato___Tomato_mosaic_virus',
            'Tomato___healthy'
        ]

        # Return predictions for each model
        result = {
            "model1": {
                "name": "tomato_disease.h5",
                "accuracy": round(float(np.max(prediction1)) * 100, 2),
                "predicted_class": class_names[np.argmax(prediction1)]
            },
            "model2": {
                "name": "02_CNN_VGG.h5",
                "accuracy": round(float(np.max(prediction2)) * 100, 2),
                "predicted_class": class_names[np.argmax(prediction2)]
            },
            "model3": {
                "name": "03_CNN_inception.h5",
                "accuracy": round(float(np.max(prediction3)) * 100, 2),
                "predicted_class": class_names[np.argmax(prediction3)]
            },
            "model4": {
                "name": "tomato.h5",
                "accuracy": round(float(np.max(prediction4)) * 100, 2),
                "predicted_class": class_names[np.argmax(prediction4)]
            },
            "final_prediction": {
                "accuracy": round(float(final_accuracy) * 100, 2),
                "predicted_disease": class_names[final_predicted_class]
            }
        } 

        # Clean up temporary files
        os.remove(file_location)

        return JSONResponse(content=result)
    except Exception as e:
        return JSONResponse(content={"error": str(e)}, status_code=500)

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="127.0.0.1", port=8000, reload=True)