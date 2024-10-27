<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function handleChat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'disease' => 'required|string'
        ]);

        $message = $request->input('message');
        $disease = $request->input('disease');

        // Load the disease details from the JSON file
        $json = file_get_contents(storage_path('app/public/recommendation.json'));
        $data = json_decode($json, true);

        $diseaseDetails = null;
        foreach ($data['diseases'] as $diseaseItem) {
            if ($diseaseItem['name'] === $disease) {
                $diseaseDetails = $diseaseItem;
                break;
            }
        }

        if (!$diseaseDetails) {
            return response()->json(['response' => 'Sorry, I could not find details for the specified disease.']);
        }

        // Call the Hugging Face API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('HUGGINGFACE_API_KEY')
        ])->post('https://api-inference.huggingface.co/models/microsoft/DialoGPT-medium', [
            'inputs' => "Disease: $disease. Details: " . json_encode($diseaseDetails) . ". User message: $message"
        ]);

        if ($response->failed()) {
            return response()->json(['response' => 'Sorry, there was an error processing your request.']);
        }

        $botResponse = $response->json()['generated_text'] ?? 'Sorry, I could not generate a response.';

        return response()->json(['response' => $botResponse]);
    }
}