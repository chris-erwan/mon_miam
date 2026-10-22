<?php

namespace App\Modules\Menu\Models;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'restaurant_id',
        'category_id',
        'name',
        'description',
        'price',
        'available',
        'is_daily',
    ];

    protected function casts(): array
    {
        return [
            'restaurant_id' => 'integer',
            'category_id' => 'integer',
            'price' => 'integer',
            'available' => 'boolean',
            'is_daily' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
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
        $this->attributes['available'] = false;
    }

    /**
     * Marque le plat comme disponible.
     */
    public function markAsAvailable(): void
    {
        $this->attributes['available'] = true;
    }
}