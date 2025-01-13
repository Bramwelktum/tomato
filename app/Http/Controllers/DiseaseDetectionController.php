<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\DiseaseDetection;

class DiseaseDetectionController extends Controller
{
    public function detectDisease(Request $request)
    {
        $request->validate([
            'tomato_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'language' => 'required|string|in:english,kiswahili'
        ]);

        // Store the uploaded image temporarily
        $path = $request->file('tomato_image')->store('tomato_images', 'public');

        // Send the image to the FastAPI server for disease detection
        $response = Http::attach(
            'file', file_get_contents(storage_path('app/public/' . $path)), basename($path)
        )->post('http://127.0.0.1:8000/predict/');

        // Log the response for debugging
        Log::info('FastAPI response: ' . $response->body());

        // Check if the response contains the expected data
        if ($response->successful() && isset($response->json()['model1'])) {
            $prediction = $response->json();
        } else {
            // Handle the error case
            $prediction = [
                'model1' => ['predicted_class' => 'Error', 'accuracy' => 0],
                'model2' => ['predicted_class' => 'Error', 'accuracy' => 0],
                'model3' => ['predicted_class' => 'Error', 'accuracy' => 0],
                'model4' => ['predicted_class' => 'Error', 'accuracy' => 0],
                'final_prediction' => ['predicted_disease' => 'Error', 'accuracy' => 0]
            ];
        }

        // Load the disease details from the JSON file based on the selected language
        $language = $request->input('language');
        $predictedDisease = $prediction['final_prediction']['predicted_disease'];
        Log::info('Predicted Disease: ' . $predictedDisease);
        $diseaseDetails = $this->getDiseaseDetails($predictedDisease, $language);

        // Log the disease details for debugging
        Log::info('Disease Details: ' . json_encode($diseaseDetails));

        // Save the image path and recommendation details to the database
        if ($diseaseDetails) {
            DiseaseDetection::create([
                'image_path' => $path,
                'disease_name' => $diseaseDetails['name'],
                'description' => $diseaseDetails['description'],
                'remedy' => $diseaseDetails['recommendations']['remedy'],
                'other_recommendations' => $diseaseDetails['recommendations']['other'],
                'user_id' => Auth::id()
            ]);
        }

        // Return the prediction result and disease details to the user
        return view('dashboard', ['prediction' => $prediction, 'diseaseDetails' => $diseaseDetails, 'language' => $language]);
    }

    private function getDiseaseDetails($diseaseName, $language)
    {
        $json = file_get_contents(storage_path('app/public/recommendation.json'));
        $data = json_decode($json, true);

        Log::info('Looking for disease: ' . $diseaseName);

        foreach ($data['diseases'] as $disease) {
            if ($disease['name'] === $diseaseName) {
                return [
                    'name' => $disease['name'],
                    'description' => $disease['description'][$language],
                    'recommendations' => $disease['recommendations'][$language]
                ];
            }
        }

        return null;
    }

    public function history()
{
    $user = Auth::user();
    $diseaseDetections = DiseaseDetection::where('user_id', $user->id)->get();

    return view('history', ['diseaseDetections' => $diseaseDetections]);
}
}