<?php

namespace App\Http\Controllers;

use App\Http\Services\GetConsultsService;
use App\Http\Services\SearchDictionaryApiService;
use App\Models\FavoritesWord;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ConsultedDataController extends Controller
{
    public function index(Request $request) : JsonResponse {
        try{

            $consultService  = app()->makeWith(GetConsultsService::class,[
                'request' => $request
            ]);

            $response = $consultService->toGetData();

            if(!$response['status']){
                throw new \Exception($response);
            }
           
            return response()->json([
                "status"  => true,
                "response" => $response['response'],
            ],201);
        }catch(\Exception $e){
            return response()->json([
                "status"  => false,
                "response" => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
