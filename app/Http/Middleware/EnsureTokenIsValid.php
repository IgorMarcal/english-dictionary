<?php

namespace App\Http\Middleware;

use App\Http\Services\TokenValidator;
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

    protected $tokenValidator;

    public function __construct(TokenValidator $tokenValidator)
    {
        $this->tokenValidator = $tokenValidator;
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        try {
            $user = $this->tokenValidator->validate($token);
            $request->attributes->add(['auth_user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }

        return $next($request);
    }
}
