<?php

namespace Tests\Unit\Auth;

use App\Modules\Auth\Models\Etudiant;
use App\Modules\Parametres\Models\Setting;
use App\Shared\Enums\Role;
use Tests\TestCase;

class EtudiantTest extends TestCase
{
    public function test_etudiant_has_the_student_role(): void
    {
        $this->assertSame([Role::Student], Etudiant::roles());
        $this->assertSame(Role::Student, Etudiant::defaultRole());
    }

    public function test_etudiant_filters_its_queries_on_the_student_role(): void
    {
        $query = Etudiant::query()->toBase();

        $this->assertSame([Role::Student->value], $query->getBindings());
    }

    public function test_default_settings_match_the_specification(): void
    {
        $this->assertSame(1000, Setting::DEFAULTS['taux_points_montant']);
        $this->assertSame(15, Setting::DEFAULTS['seuil_points']);
        $this->assertSame(1000, Setting::DEFAULTS['valeur_reduction']);
        $this->assertSame(12, Setting::DEFAULTS['validite_points_mois']);
        $this->assertSame(5, Setting::DEFAULTS['points_parrainage']);
    }
}