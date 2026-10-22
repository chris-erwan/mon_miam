<?php

namespace App\Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class CookieConsent extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'accepted', 'consented_at'];

    protected function casts(): array
    {
        return [
            'user_id'  => 'integer',
            'accepted' => 'boolean',
        ];
    }
}
