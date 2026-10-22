<?php

namespace App\Modules\Promotions\Models;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Promotion extends Model
{
    protected $fillable = [
        'restaurant_id',
        'title',
        'type',
        'image_url',
        'starts_at',
        'ends_at',
        'created_by',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (isset($this->attributes['starts_at'], $this->attributes['ends_at'])) {
            if ($this->attributes['ends_at'] < $this->attributes['starts_at']) {
                throw new InvalidArgumentException('La date de fin doit être postérieure à la date de début');
            }
        }
    }

    public function isActiveAt(string $date): bool
    {
        return $date >= $this->starts_at && $date <= $this->ends_at;
    }
}
