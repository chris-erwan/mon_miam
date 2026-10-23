<?php

namespace Tests\Unit\Auth;

use App\Modules\Auth\Events\StudentRegistered;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    public function test_password_needs_an_uppercase_letter_and_a_digit(): void
    {
        $this->assertTrue(AuthService::passwordIsValid('Test1234'));
        $this->assertFalse(AuthService::passwordIsValid('abcdefgh'));
        $this->assertFalse(AuthService::passwordIsValid('Abcdefgh'));
        $this->assertFalse(AuthService::passwordIsValid('abcdefg1'));
        $this->assertFalse(AuthService::passwordIsValid('Ab1'));
    }

    public function test_the_fidelite_module_listens_to_student_registration(): void
    {
        $this->assertNotEmpty(Event::getListeners(StudentRegistered::class));
    }
}