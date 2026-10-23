<?php

namespace App\Modules\Commandes\Enums;

enum OrderStatus: string
{
    case Pending = 'EN_ATTENTE';
    case Validated = 'VALIDEE';
    case Preparing = 'EN_PREPARATION';
    case Ready = 'PRETE';
    case Delivered = 'LIVREE';
    case Cancelled = 'ANNULEE';

    /** @return array<int, self> */
    public function next(): array
    {
        return match ($this) {
            self::Pending => [self::Validated, self::Cancelled],
            self::Validated => [self::Preparing],
            self::Preparing => [self::Ready],
            self::Ready => [self::Delivered],
            self::Delivered, self::Cancelled => [],
        };
    }

    public function canGoTo(self $target): bool
    {
        return in_array($target, $this->next(), true);
    }
}