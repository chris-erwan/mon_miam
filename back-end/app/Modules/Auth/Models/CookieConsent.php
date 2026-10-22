<?php

namespace App\Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CookieConsent extends Model
{
    protected $table = 'consentements_cookies';

    protected $primaryKey = 'id_consentement';

    public $timestamps = false;

    protected $fillable = [
        'id_utilisateur',
        'identifiant_visiteur',
        'accepte',
        'cookies_analytiques',
        'date_consentement',
    ];

    protected $casts = [
        'accepte'             => 'boolean',
        'cookies_analytiques' => 'boolean',
        'date_consentement'   => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }
}