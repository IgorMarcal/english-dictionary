<?php

namespace App\Http\Services;

use App\Models\ConsultHistory;
use Illuminate\Http\Request;

class GetConsultsService 
{
    private array $params;
    public function __construct(private Request $request){
        $this->params = $request->query();
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
}
