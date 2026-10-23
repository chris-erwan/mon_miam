<?php

namespace App\Modules\Restaurants\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    public const STATUS_OPEN = 'OUVERT';

    protected $table = 'restaurants';

    protected $primaryKey = 'id_restaurant';

    public $timestamps = false;

    protected $fillable = [
        'nom',
        'adresse',
        'telephone',
        'heure_ouverture',
        'heure_fermeture',
        'statut',
    ];

    public function isOpen(): bool
    {
        return $this->statut === self::STATUS_OPEN;
    }
}