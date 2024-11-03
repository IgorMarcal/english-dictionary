<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PersonalAccessToken;


class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            $token = $request->bearerToken();
        
            if (!$token) {
                throw new \Exception('Token não fornecido', 401);
            }
    
            $parts = explode('|', $token);
            if (count($parts) !== 2) {
                throw new \Exception('Token inválido.', 401);
            }
    
            $plainTextToken = $parts[1];
    
            $personalAccessToken = PersonalAccessToken::where('token', hash('sha256', $plainTextToken))->first();
    
            if (!$personalAccessToken) {
                throw new \Exception('Token inválido.', 401);
            }

            if ($personalAccessToken->expires_at && $personalAccessToken->expires_at < now()) {
                throw new \Exception('Token expirado.', 401);
            }
            
            return $next($request);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
        
    }
}
