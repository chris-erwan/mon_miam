<?php

namespace App\Modules\Auth\Events;

use App\Modules\Auth\Models\Etudiant;
use Illuminate\Foundation\Events\Dispatchable;

class StudentRegistered
{
    use Dispatchable;

    public function __construct(
        public readonly Etudiant $student,
        public readonly ?string $referralCode = null,
    ) {
    }
}