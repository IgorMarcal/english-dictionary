<?php

namespace App\Http\Services;

use App\Models\ConsultHistory;
use App\Models\FavoritesWord;
use Illuminate\Http\Request;

class GetConsultsService 
{
    private array $params;
    private string $user;
    public function __construct(private Request $request){
        $this->params = $request->query();
        $this->user = $request->attributes->get('auth_user');
    }
    public function toGetData(): array{
        
        try{
            $word = $this->params['search'];
            $perPage = $this->params['limit'];

            $getdata = ConsultHistory::where('word','like', "%$word%")->paginate($perPage);
            $response = [ 
                "results" => $getdata->pluck('word')->toArray(),
                "totalDocs" => $getdata->total(),
                "page" => $getdata->currentPage(),
                "totalPages" => $getdata->lastPage(),
                "hasNext" => $getdata->hasMorePages(),
                "hasPrev" => $getdata->currentPage() > 1
            ];

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

    public function toGetHistoryData(): array
    {
        try{
            $getdata = ConsultHistory::where('user_id','=', $this->user)
                ->paginate(5);

            $response = [ 
                "results" => $getdata->map(function ($item) {
                    return [
                        'word' => $item->word,
                        'added' => $item->created_at,
                    ];
                })->toArray(),
                "totalDocs" => $getdata->total(),
                "page" => $getdata->currentPage(),
                "totalPages" => $getdata->lastPage(),
                "hasNext" => $getdata->hasMorePages(),
                "hasPrev" => $getdata->currentPage() > 1
            ];

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

    public function toGetHistoryFavorites(): array
    {
        try{
            $getdata = FavoritesWord::where('user_id','=', $this->user)
                ->paginate(5);

            $response = [ 
                "results" => $getdata->map(function ($item) {
                    return [
                        'word' => $item->word,
                        'added' => $item->created_at,
                    ];
                })->toArray(),
                "totalDocs" => $getdata->total(),
                "page" => $getdata->currentPage(),
                "totalPages" => $getdata->lastPage(),
                "hasNext" => $getdata->hasMorePages(),
                "hasPrev" => $getdata->currentPage() > 1
            ];

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
