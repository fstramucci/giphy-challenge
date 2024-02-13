<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class GifController extends Controller
{
    /**
     * Search gifs.
     */
    public function search(Request $request)
    {
        $validatedData = $request->validate([
            'query' => 'required|string|max:50',
            'limit' => 'integer|max:50|min:0',
            'offset' => 'integer|max:4999|min:0'
        ]);

        $queryParams = array_filter([
            'api_key' => env('GIPHY_KEY'),
            'q' => $validatedData['query'],
            'limit' => $validatedData['limit'] ?? null,
            'offset' => $validatedData['offset'] ?? null,
        ]);
    
        $response = Http::get('https://api.giphy.com/v1/gifs/search', $queryParams);

        return $response;
    }

    /**
     * Show specific gif.
     */
    public function show(Request $request, string $id)
    {
        $validatedData = Validator::make(['id' => $request->route('id')], [
            'id' => 'required|string|max:64'
        ])->validate();

        $response = Http::get('https://api.giphy.com/v1/gifs/'.$validatedData['id'], [
            'api_key' => env('GIPHY_KEY')
        ]);

        return $response;
    }
}