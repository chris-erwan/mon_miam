<?php

namespace App\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Order\Models\Order;
use InvalidArgumentException;

/**
 * Class Payment
 *
 * Représente la transaction de règlement d'une commande selon le diagramme.
 */
class Payment extends Model
{
    public const PROVIDERS = ['orange_money', 'mtn_momo', 'especes'];

    protected $fillable = [
        'order_id',
        'provider',
        'amount',
        'status',
        'transaction_ref',
    ];

    protected function casts(): array
    {
        return [
            'order_id' => 'integer',
            'amount'   => 'integer',
        ];
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (isset($this->attributes['amount']) && (int) $this->attributes['amount'] <= 0) {
            throw new InvalidArgumentException('Le montant du paiement doit être supérieur à zéro');
        }

        if (isset($this->attributes['provider']) && !in_array($this->attributes['provider'], self::PROVIDERS, true)) {
            throw new InvalidArgumentException('Opérateur de paiement non pris en charge');
        }
    }

    public function markAsSuccessful(string $transactionRef): void
    {
        $this->attributes['status'] = 'reussi';
        $this->attributes['transaction_ref'] = $transactionRef;
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
