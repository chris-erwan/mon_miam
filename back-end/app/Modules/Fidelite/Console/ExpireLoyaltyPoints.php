<?php

namespace App\Modules\Fidelite\Console;

use App\Modules\Fidelite\Services\LoyaltyService;
use Illuminate\Console\Command;

class ExpireLoyaltyPoints extends Command
{
    protected $signature = 'loyalty:expire-points';

    protected $description = 'Supprime les points de fidélité non utilisés après la durée de validité (12 mois).';

    public function handle(LoyaltyService $loyalty): int
    {
        $expired = $loyalty->expireOldPoints();
        $this->info("Points expirés : {$expired}");

        return self::SUCCESS;
    }
}