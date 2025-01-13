# Farm Fresh Tomato Leaf Detection System

## Overview
The **Farm Fresh Tomato Leaf Detection System** is an advanced tool designed to identify and diagnose diseases in tomato leaves. By leveraging cutting-edge deep learning techniques and a user-friendly interface, this system empowers farmers, researchers, and agricultural professionals to detect diseases early and implement effective solutions.

## Features
- **Disease Detection**: Accurately identifies common diseases affecting tomato plants.
- **Ensemble Model Architecture**: Utilizes multiple CNN models for robust and reliable predictions.
- **AI Recommendations**: Provides actionable insights and solutions for identified diseases.
- **Language Support**: Offers AI-based translation of recommendations from English to Swahili.
- **Web Integration**: Fully functional web interface built using Laravel for ease of access.

## How It Works

### 1. Data Collection
- The system is trained on a labeled dataset of tomato leaves categorized into diseased and healthy groups.
- Data preprocessing involves resizing, augmenting, and normalizing the images to enhance model performance.

### 2. Model Training
- Multiple CNN models are trained independently to classify tomato leaves into healthy or diseased categories.
- Ensemble methods are applied to combine predictions from different models, ensuring higher accuracy and reliability.

### 3. Disease Detection
- Users upload an image of a tomato leaf through the web interface.
- The uploaded image is processed and analyzed by the ensemble model, which provides a prediction along with a confidence score.

### 4. Recommendations
- Once a disease is detected, the system generates tailored recommendations to address the issue.
- Recommendations are based on best practices and expert knowledge in plant pathology.

### 5. Language Translation
- For ease of use, the recommendations can be translated from English to Swahili, ensuring accessibility for a wider audience.

## System Requirements
- **Frontend**: React.js for user interactions.
- **Backend**: Laravel for application logic and API integration.
- **Machine Learning Models**: TensorFlow/Keras for CNN-based predictions.
- **Database**: MySQL for storing user data and prediction logs.
- **Hosting Environment**: Compatible with cloud platforms or local servers.

## How to Use

### 1. Upload an Image
- Navigate to the web interface.
- Select or drag and drop an image of a tomato leaf.

### 2. View Predictions
- After uploading, the system processes the image and displays:
  - Disease status (Healthy or Diseased).
  - Confidence score for the prediction.

### 3. Access Recommendations
- If a disease is detected, view detailed recommendations to treat and prevent the disease.
- Choose to translate recommendations to Swahili if needed.

### 4. Download or Share Results
- Users can download a summary of the results or share them directly with their networks.

## Example Workflow
1. A farmer observes discoloration on tomato leaves and suspects a disease.
2. The farmer captures a clear image of the affected leaf using a smartphone.
3. The image is uploaded to the Farm Fresh Tomato Leaf Detection System.
4. Within seconds, the system identifies the disease (e.g., Early Blight) with a confidence score of 95%.
5. The farmer receives actionable recommendations in Swahili to address the issue.

## Benefits
- **Early Detection**: Reduces the risk of disease spread.
- **Cost-Effective**: Minimizes resource wastage by targeting specific issues.
- **User-Friendly**: Simplifies the diagnostic process for non-technical users.
- **Localized Support**: Promotes inclusivity with multilingual recommendations.

## Installation and Setup

### Prerequisites
- Node.js and npm installed.
- Laravel environment setup.
- TensorFlow and Python environment for model deployment.
- MySQL database.

### Steps
1. Clone the repository:
   ```bash
   git clone https://github.com/username/farm-fresh-tomato-leaf-detection.git
   ```
2. Navigate to the project directory:
   ```bash
   cd farm-fresh-tomato-leaf-detection
   ```
3. Install frontend dependencies:
   ```bash
   npm install
   ```
4. Set up Laravel backend:
   - Install dependencies:
     ```bash
     composer install
     ```
   - Set environment variables in `.env` file.
   - Migrate the database:
     ```bash
     php artisan migrate
     ```
5. Train or load pre-trained models:
   - Navigate to the `models` directory.
   - Use the provided Jupyter notebooks to train models or load pre-trained weights.

6. Start the application:
   ```bash
   npm run dev # For frontend
   php artisan serve # For backend
   ```

### Accessing the Application
- Open your web browser and navigate to `http://localhost:8000` (or the configured domain).

## Contributing
We welcome contributions from the community! Please follow these steps to contribute:
1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Submit a pull request with a detailed description of your changes.

## License
This project is licensed under the [MIT License](LICENSE).

## Support
For questions or support, please contact us at [support@farmfreshdetection.com](mailto:support@farmfreshdetection.com).

---
We hope this system helps you ensure healthier tomato crops and improved agricultural outcomes!
