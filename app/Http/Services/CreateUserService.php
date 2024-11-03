<?php

namespace App\Http\Services;

use App\Models\PersonalAccessToken;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CreateUserService 
{
    private string $name;
    private string $email;
    private string $password;

    public function __construct(Request $request) {
        $this->name     = $request->input('name');
        $this->email    = $request->input('email');
        $this->password = $request->input('password');
    }

    public function toCreate(): array{
        
        try{
            $searchAccount = Users::where('email' , '=', $this->email)->first();
            if($searchAccount){
                throw new \Exception("User already exists!", 409);
            }
            
            $uuid = Str::uuid();
            $createAccount = Users::create([
                'id'        => $uuid,
                'name'      => $this->name,
                'email'     => $this->email,
                'password'  => Crypt::encryptString($this->password),
            ]);

            $token = $createAccount->createToken('BackendDictionary', [], Carbon::now()->addHours(2))->plainTextToken;

            return [
                'status'    => true,
                'response'  => [
                    'id'    => $uuid,
                    'name' => $createAccount->name,
                    'token' => "Bearer $token",
                ],
            ];

        }catch (\Exception $e){
            return [
                'status'   => false,
                'response' => $e->getMessage()
            ];
        }
    }
}
