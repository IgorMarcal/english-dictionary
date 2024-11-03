<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'tokenable_id',
        'tokenable_type',
        'name',
        'token',
        'abilities',
        'last_used_at',
        'expires_at',
    ];

    public function tokenable()
    {
        return $this->morphTo();
    }
}
