<?php

namespace App\Modules\Commandes\Models;

use App\Modules\Menu\Models\MenuItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'lignes_commande';

    protected $primaryKey = 'id_ligne';

    public $timestamps = false;

    protected $fillable = ['id_commande', 'id_element', 'quantite', 'prix_unitaire'];

    protected function casts(): array
    {
        return [
            'quantite' => 'integer',
            'prix_unitaire' => 'integer',
            'sous_total' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id_commande');
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'id_element');
    }
}