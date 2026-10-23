<?php

namespace App\Modules\Menu\Models;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    protected $table = 'elements_menu';

    protected $primaryKey = 'id_element';

    public $timestamps = false;

    protected $fillable = [
        'id_categorie',
        'nom',
        'description',
        'prix',
        'image',
        'disponible',
        'plat_du_jour',
    ];

    protected function casts(): array
    {
        return [
            'id_categorie' => 'integer',
            'prix' => 'integer',
            'disponible' => 'boolean',
            'plat_du_jour' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'id_categorie');
    }

    /**
     * Modifie le prix en s'assurant qu'il reste positif ou nul.
     */
    public function setPrice(int $price): void
    {
        if ($price < 0) {
            throw new InvalidArgumentException('Le prix ne peut pas être négatif');
        }

        $this->attributes['price'] = $price;
    }

    /**
     * Marque le plat comme indisponible (rupture de stock).
     */
    public function markAsUnavailable(): void
{
    $this->attributes['disponible'] = false;
}

    /**
     * Marque le plat comme disponible.
     */
    public function markAsAvailable(): void
{
    $this->attributes['disponible'] = true;
}
}