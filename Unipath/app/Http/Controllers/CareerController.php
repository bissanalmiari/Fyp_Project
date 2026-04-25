<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Career;
use App\Models\Major;
use App\Models\Category;

class CareerController extends Controller{

    public function index(Request $request)
{
    $majors = Major::where('is_trendy', 1)->get();
    $categories = Category::all();

    $careers = Career::with('category')
        ->when($request->category, function ($query) use ($request) {
            $query->where('category_id', $request->category);
        })
        ->when($request->search, function ($query) use ($request) {
    $search = strtolower($request->search);

    $query->whereRaw('LOWER(title) LIKE ?', ['%' . $search . '%']);
})
->get();

    $inDemandCareers = Career::where('in_demand', 1)
        ->inRandomOrder()
        ->take(4)
        ->get();

    if ($request->ajax()) {
        return view('career.partials.careers-grid', compact('careers'))->render();
    }

    return view('career.career', compact('careers', 'majors', 'categories','inDemandCareers'));
}

    

   public function match(Request $request)
{
    $request->validate([
        'major' => 'required|string|max:255'
    ]);

    $major = $request->input('major');

    $aiCareers = [];
    $aiError = null;

    try {
        $apiKey = config('services.huggingface.api_key');
        if ($apiKey) {
            $response = Http::timeout(15)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->post("https://router.huggingface.co/v1/chat/completions", [
                'model' => config('services.huggingface.model'),
                'messages' => [
                    [
                        'role' => 'user', 
                        'content' => "List 3 career job titles for a student who studied {$major}. Return only job titles separated by commas."
                    ],
                ],
                'max_tokens' => 100,
            ]);

            if ($response->successful()) {
                $json = $response->json();
                $text = $json['choices'][0]['message']['content'] ?? '';

                if ($text) {
                    $cleanText = preg_replace('/^.*?:\s*/is', '', $text);
                    $lines = preg_split("/[,\r\n]+/", $cleanText);

                    $aiCareers = array_values(array_unique(array_filter(array_map(function ($line) {
                        $clean = trim(preg_replace('/^[\d\.\-\•\*\)\s]+/', '', $line));
                        return (strlen($clean) > 3) ? $clean : null;
                    }, $lines))));

                    $aiCareers = array_slice($aiCareers, 0, 5);
                }
            } else {
                $aiError = 'API Error: ' . $response->status();
            }
        } else {
            $aiError = 'API Key not configured.';
        }
    } catch (\Exception $e) {
        $aiError = $e->getMessage();
    }

    return response()->json([
        'major' => $major,
        'ai_careers' => $aiCareers,
        'ai_error' => $aiError,
    ]);
}

}