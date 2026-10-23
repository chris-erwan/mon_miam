<?php

namespace App\Modules\Fidelite\Models;

use App\Modules\Auth\Models\Etudiant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyTransaction extends Model
{
    public const TYPE_ORDER      = 'COMMANDE';
    public const TYPE_REFERRAL   = 'PARRAINAGE';
    public const TYPE_SPENT      = 'UTILISATION';
    public const TYPE_EXPIRATION = 'EXPIRATION';

    protected $table = 'transactions_fidelite';

    protected $primaryKey = 'id_transaction';

    public $timestamps = false;

    protected $fillable = [
        'id_utilisateur', 'id_commande', 'type_transaction',
        'points', 'points_restants', 'date_transaction', 'date_expiration',
    ];

    protected $casts = [
        'points'           => 'integer',
        'points_restants'  => 'integer',
        'date_transaction' => 'datetime',
        'date_expiration'  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class, 'id_utilisateur');
    }

    public function scopeUsableEarnings(Builder $query): Builder
    {
        return $query->where('points', '>', 0)->where('points_restants', '>', 0);
    }
}