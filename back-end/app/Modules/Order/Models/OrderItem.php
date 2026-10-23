<?php

namespace App\Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Menu\Models\MenuItem;
use InvalidArgumentException;

/**
 * Class OrderItem
 *
 * Représente une ligne de commande (plat, quantité, prix unitaire).
 */
class OrderItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'quantity',
        'unit_price',
    ];

    protected function casts(): array
    {
        return [
            'order_id'     => 'integer',
            'menu_item_id' => 'integer',
            'quantity'     => 'integer',
            'unit_price'   => 'integer',
        ];
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (isset($this->attributes['quantity']) && (int) $this->attributes['quantity'] <= 0) {
            throw new InvalidArgumentException('La quantité doit être supérieure à zéro');
        }

        if (isset($this->attributes['unit_price']) && (int) $this->attributes['unit_price'] < 0) {
            throw new InvalidArgumentException('Le prix unitaire ne peut pas être négatif');
        }
    }

    /**
     * Calcule le sous-total de la ligne.
     */
    public function getSubtotal(): int
    {
        return (int) ($this->unit_price * $this->quantity);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }
}
