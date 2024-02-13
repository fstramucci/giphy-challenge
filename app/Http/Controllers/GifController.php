<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\Favourite;

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

    /**
     * Save favourite gif to user
     */
    public function save(Request $request)
    {
        $validatedData = $request->validate([
            'gif_id' => 'required|string|max:64',
            'alias' => 'required|string|max:128',
            'user_id' => 'required|numeric|exists:users,id'
        ]);
    
        $favourite = new Favourite;
        $favourite->gif_id = $validatedData['gif_id'];
        $favourite->alias = $validatedData['alias'];
        $favourite->user_id = $validatedData['user_id'];
    
        if ($favourite->save()) {
            return response()->json(['message' => 'Favourite saved successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to save favourite'], 500);
        }
    }
}