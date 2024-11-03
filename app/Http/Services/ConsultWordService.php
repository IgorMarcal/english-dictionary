<?php

namespace App\Http\Services;

use App\Models\ConsultHistory;
use DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
// use Ramsey\Uuid\Nonstandard\Uuid;
use Illuminate\Support\Str;


class ConsultWordService 
{
    private string $urlApi;
    private string $user;
    public function __construct(Request $request){
        $this->user = $request->attributes->get('auth_user');
        $this->urlApi = env("DICTIONARY_API_URL");
    }

    public function toGetWord(string $word): array{
        
        try{
            $url = $this->urlApi . $word;
            $consult = Http::get($url)->json();

            if(is_null($consult)){
                throw new \Exception("Word not found");
            }

            $saveOrUpdate = ConsultHistory::firstOrCreate(['word' => $word, 'user_id' => $this->user]);

            return [
                'status'    => true,
                'response'  => $consult,
            ];

        }catch (\Exception $e){
            return [
                'status'   => false,
                'response' => $e->getMessage()
            ];
        }
    }
}
