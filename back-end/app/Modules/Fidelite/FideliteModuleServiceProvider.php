<?php

namespace App\Modules\Fidelite;

use App\Modules\Auth\Events\StudentRegistered;
use App\Modules\Fidelite\Listeners\ApplyReferralCode;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class FideliteModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(StudentRegistered::class, ApplyReferralCode::class);

        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');
    }
}