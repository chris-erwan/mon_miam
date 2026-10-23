<?php

namespace App\Modules\Parametres\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    public const DEFAULT_RESTAURANT = 1;

    protected $table = 'parametres';

    protected $primaryKey = 'id_parametre';

    public $timestamps = false;

    protected $fillable = ['id_restaurant', 'cle', 'valeur', 'description'];

    public const DEFAULTS = [
        'taux_points_montant'  => 1000, // 1000 F dépensés = 1 point
        'seuil_points'         => 15,   // 15 points ...
        'valeur_reduction'     => 1000, // ... = 1000 F de réduction
        'validite_points_mois' => 12,   // les points expirent après 12 mois
        'points_parrainage'    => 5,    // points gagnés par le parrain
    ];

    public static function get(string $cle, int $restaurantId = self::DEFAULT_RESTAURANT): mixed
    {
        $value = Cache::remember(
            "parametre:{$restaurantId}:{$cle}",
            3600,
            fn () => static::query()
                ->where('id_restaurant', $restaurantId)
                ->where('cle', $cle)
                ->value('valeur')
        );

        return $value ?? (self::DEFAULTS[$cle] ?? null);
    }

    public static function int(string $cle, int $restaurantId = self::DEFAULT_RESTAURANT): int
    {
        return (int) self::get($cle, $restaurantId);
    }

    public static function put(string $cle, mixed $valeur, int $restaurantId = self::DEFAULT_RESTAURANT, ?string $description = null): self
    {
        $setting = static::query()->updateOrCreate(
            ['id_restaurant' => $restaurantId, 'cle' => $cle],
            array_filter(['valeur' => (string) $valeur, 'description' => $description], fn ($v) => $v !== null)
        );
        Cache::forget("parametre:{$restaurantId}:{$cle}");

        return $setting;
    }
}