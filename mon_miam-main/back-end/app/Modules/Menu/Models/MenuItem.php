<?php

namespace App\Modules\Menu\Models;

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
}