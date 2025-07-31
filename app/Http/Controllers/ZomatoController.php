<?php

namespace App\Http\Controllers;

use App\Models\ApiLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ZomatoController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->query('q');

        try {
            $response = Http::withOptions([
                'verify' => false
            ])->get('https://maps.googleapis.com/maps/api/place/textsearch/json', [
                'query' => $query . ' restaurant',
                'region' => 'id',
                'key' => env('ZOMATO_API_KEY')
            ]);


            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil data dari Google Places',
                'message' => $e->getMessage()
            ], 500);
        }
    }





    public function menu($id)
    {
        return response()->json(['message' => "Get menu of restaurant ID {$id}"]);
    }

    public function review($id)
    {
        $response = Http::withHeaders([
            'user-key' => env('ZOMATO_API_KEY')
        ])->get("https://maps.googleapis.com/maps/api/place/textsearch/json", [
            'res_id' => $id
        ]);

        return $response->json();
    }
}
