<?php

namespace App\Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    public $timestamps = false;

    protected $fillable = ['email', 'token', 'created_at', 'expires_at'];

    public function isExpiredAt(string $dateTime): bool
    {
        return $dateTime > $this->expires_at;
    }
}
