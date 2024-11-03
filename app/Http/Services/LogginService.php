<?php

namespace App\Http\Services;

use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class LogginService 
{
    private string $email;
    private string $password;

    public function __construct(array $credentials) {
        $this->email    = $credentials['email'];
        $this->password = $credentials['password'];
    }

    public function toLogin(): array{
        
        try{
            $user = Users::where('email', $this->email)->first();
            
            if (!$user || Crypt::decryptString($user->password) !== $this->password) {
               throw new \Exception("Credentials invalid", 401);
            }

            $token = $user->createToken('BackendDictionary', [], Carbon::now()->addHours(2))->plainTextToken;
 
            return [
                'status'    => true,
                'response'  => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'token' => "Bearer $token"
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
