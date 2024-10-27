<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
                'final_prediction' => ['predicted_disease' => 'Error', 'accuracy' => 0]
            ];
        }

        // Delete the temporary image
        Storage::disk('public')->delete($path);

        // Load the disease details from the JSON file based on the selected language
        $language = $request->input('language');
        $diseaseDetails = $this->getDiseaseDetails($prediction['final_prediction']['predicted_disease'], $language);

        // Log the disease details for debugging
        Log::info('Disease Details: ' . json_encode($diseaseDetails));

        // Return the prediction result and disease details to the user
        return view('dashboard', ['prediction' => $prediction, 'diseaseDetails' => $diseaseDetails, 'language' => $language]);
    }

    private function getDiseaseDetails($diseaseName, $language)
    {
        $json = file_get_contents(storage_path('app/public/recommendation.json'));
        $data = json_decode($json, true);

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
}