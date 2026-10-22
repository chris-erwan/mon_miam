<?php

namespace App\Modules\Support\Models;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Review extends Model
{
    protected $fillable = ['order_id', 'comment', 'rating'];

    protected function casts(): array
    {
        return [
            'order_id' => 'integer',
            'rating'   => 'integer',
        ];
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (isset($this->attributes['rating'])) {
            $rating = (int) $this->attributes['rating'];
            if ($rating < 1 || $rating > 5) {
                throw new InvalidArgumentException('La note doit être comprise entre 1 et 5');
            }
        }
    }
}
