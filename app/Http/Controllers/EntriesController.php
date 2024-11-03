<?php

namespace App\Http\Controllers;

use App\Http\Services\SearchDictionaryApiService;
use App\Models\FavoritesWord;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EntriesController extends Controller
{
    public SearchDictionaryApiService $consultService;
    public function __construct(Request $request) {
        $this->consultService = app()->makeWith(SearchDictionaryApiService::class,[
            'request' => $request
        ]);
    }
    public function word(string $word) : JsonResponse {
        try{
            $search = $this->consultService->toSearchWord($word);

            if(!$search['status']){
                throw new \Exception($search['response'], 404);
            }

            return response()->json([
                "status"  => true,
                "response" => $search['response'],
            ],201);
        }catch(\Exception $e){
            return response()->json([
                "status"  => false,
                "response" => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function unfavorite(string $word) : JsonResponse {
        try{
            $unfavorite = FavoritesWord::where('word','=',$word)->delete();

            if (!$unfavorite) {
                throw new \Exception("Could not unfavorite word", 200);
            }

            return response()->json([
                "status"  => true,
                "response" => "Word unfavorited",
            ],201);
        }catch(\Exception $e){
            return response()->json([
                "status"  => false,
                "response" => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
