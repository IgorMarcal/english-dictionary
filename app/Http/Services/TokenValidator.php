<?php 

namespace App\Http\Services;

use App\Models\PersonalAccessToken;

class TokenValidator {
    public function validate($token)
    {
        if (!$token) {
            throw new \Exception('Token não fornecido');
        }

        $parts = explode('|', $token);
        if (count($parts) !== 2) {
            throw new \Exception('Token inválido.');
        }

        $plainTextToken = $parts[1];
        $personalAccessToken = PersonalAccessToken::where('token', hash('sha256', $plainTextToken))->first();
        if (!$personalAccessToken || ($personalAccessToken->expires_at && $personalAccessToken->expires_at < now())) {
            throw new \Exception('Token expirado ou inválido.');
        }

        return $personalAccessToken->tokenable_id;
    }
}