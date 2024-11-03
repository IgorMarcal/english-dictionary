<?php

namespace App\Http\Controllers;

use App\Http\Services\GetConsultsService;
use App\Models\Users;
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

    public function user(Request $request) : JsonResponse {
        try{
            $user = Users::where('id','=', $request->attributes->get('auth_user'))->get()->toArray();
            if(!$user){
                throw new \Exception("User not found");
            }

            $response = [
                'id'     => $user[0]['id'],
                'Name'   => $user[0]['name'],
                'Email'  => $user[0]['email'],
            ];

            return response()->json([
                "status"   => true,
                "response" => $response,
            ],200);
        }catch(\Exception $e){
            return response()->json([
                "status"  => false,
                "response" => $e->getMessage(),
            ],  404);
        }
    }

    public function userHistory(Request $request) : JsonResponse {
        try{
            $consultService  = app()->makeWith(GetConsultsService::class,[
                'request' => $request
            ]);

            $response = $consultService->toGetHistoryData();

            if(!$response['status']){
                throw new \Exception($response['response']);
            }
           
            return response()->json([
                "status"  => true,
                "response" => $response['response'],
            ],200);
        }catch(\Exception $e){
            return response()->json([
                "status"  => false,
                "response" => $e->getMessage(),
            ],  404);
        }
    }

    public function userFavorites(Request $request) : JsonResponse {
        try{
            $consultService  = app()->makeWith(GetConsultsService::class,[
                'request' => $request
            ]);

            $response = $consultService->toGetHistoryFavorites();

            if(!$response['status']){
                throw new \Exception($response['response']);
            }
           
            return response()->json([
                "status"  => true,
                "response" => $response['response'],
            ],200);
        }catch(\Exception $e){
            return response()->json([
                "status"  => false,
                "response" => $e->getMessage(),
            ],  404);
        }
    }
}
