<?php

namespace App\Http\Services;

use App\Models\ConsultHistory;
use DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class ConsultWordService 
{
    private string $urlApi;
    public function __construct(){
        $this->urlApi = env("DICTIONARY_API_URL");
    }

    public function toGetWord(string $word): array{
        
        try{
            $url = $this->urlApi . $word;
            $consult = Http::get($url)->json();

            if(is_null($consult)){
                throw new \Exception("Word not found");
            }
            
            $saveOrUpdate = ConsultHistory::firstOrCreate(['word' => $word]);

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
