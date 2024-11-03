<?php

namespace App\Http\Services;

use App\Models\FavoritesWord;
use Illuminate\Http\Request;

class SearchDictionaryApiService 
{
    private string $user;
    public function __construct(private Request $request){
        $this->user = $request->attributes->get('auth_user');
    }

    // public function toSearch(): array{
        
    //     try{
            
    //         $consult = 
    //         var_dump($this->params);exit;
 

    //         return [
    //             'status'    => true,
    //             'response'  => [
                    
    //             ],
    //         ];

    //     }catch (\Exception $e){
    //         return [
    //             'status'   => false,
    //             'response' => $e->getMessage()
    //         ];
    //     }
    // }

    public function toSearchWord(string $word): array{
        
        try{
            $consult = app()->makeWith(ConsultWordService::class,[
                'request'   => $this->request
            ]);

            $consultResponse = $consult->toGetWord($word);
            if(!$consultResponse['status']){
                throw new \Exception($consultResponse['response']);
            }
            $response = [];
            $response['message'] = 'Word retrieved successfully';
            if($this->request->segment(5) === 'favorite'){
                $response['message'] = 'Word already saved as favorite';

                if(!FavoritesWord::where('word','=', $word)->first()){
                    FavoritesWord::firstOrCreate(['word' => $word, 'user_id' => $this->user]);
                    $response['message'] = 'Word saved as favorite';
                }
            }
            $response['data'] = $consultResponse['response'];

            return [
                'status'    => true,
                'response'  => $response,
            ];

        }catch (\Exception $e){
            return [
                'status'   => false,
                'response' => $e->getMessage()
            ];
        }
    }
}
