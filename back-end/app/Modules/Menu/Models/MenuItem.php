<?php

namespace App\Modules\Menu\Models;

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
}