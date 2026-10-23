<?php

namespace App\Modules\Commandes\Models;

use App\Modules\Auth\Models\Etudiant;
use App\Modules\Restaurants\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'commandes';

    protected $primaryKey = 'id_commande';

    public $timestamps = false;

    protected $fillable = [
        'id_client',
        'id_restaurant',
        'id_employe_validateur',
        'date_commande',
        'statut',
        'mode_service',
        'heure_arrivee',
        'adresse_livraison',
        'montant_brut',
        'points_utilises',
        'reduction',
        'montant_total',
        'points_gagnes',
        'date_validation',
    ];

    protected function casts(): array
    {
        return [
            'id_client' => 'integer',
            'id_restaurant' => 'integer',
            'montant_brut' => 'integer',
            'points_utilises' => 'integer',
            'reduction' => 'integer',
            'montant_total' => 'integer',
            'points_gagnes' => 'integer',
            'date_commande' => 'datetime',
            'date_validation' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'id_commande');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class, 'id_client');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'id_restaurant');
    }
}