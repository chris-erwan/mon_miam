<?php

namespace App\Modules\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuCategory extends Model
{
    protected $table = 'categories_menu';

    protected $primaryKey = 'id_categorie';

    public $timestamps = false;

    protected $fillable = [
        'id_restaurant',
        'libelle',
        'ordre_affichage',
    ];

    protected function casts(): array
    {
        return [
            'id_restaurant' => 'integer',
            'ordre_affichage' => 'integer',
        ];
    }

    // menuItems()
    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'id_categorie');
    }
}