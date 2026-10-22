<?php

namespace Tests\Unit\Support;

use Tests\TestCase;
use App\Modules\Support\Models\Review;
use App\Modules\Support\Models\Complaint;
use InvalidArgumentException;

/**
 * Class ReviewAndComplaintTest
 *
 * Valide les avis étudiants (notes 1 à 5) et le cycle de traitement
 * d'une réclamation (employé répond, gérant valide).
 */
final class ReviewAndComplaintTest extends TestCase
{
    /**
     * Règle avis : la note doit être comprise entre 1 et 5 étoiles.
     */
    public function test_review_rating_must_be_between_one_and_five(): void
    {
        $review = new Review([
            'order_id' => 10,
            'rating'   => 5,
            'comment'  => 'Le poulet DG était délicieux et chaud !',
        ]);

        $this->assertSame(5, $review->rating);
    }

    public function test_review_rating_cannot_exceed_five(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La note doit être comprise entre 1 et 5');

        new Review(['order_id' => 10, 'rating' => 6]);
    }

    public function test_review_rating_cannot_be_zero_or_negative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La note doit être comprise entre 1 et 5');

        new Review(['order_id' => 10, 'rating' => 0]);
    }

    /**
     * Cycle réclamation : un employé répond, puis un gérant valide.
     */
    public function test_complaint_lifecycle_response_and_validation(): void
    {
        $complaint = new Complaint([
            'user_id'  => 5,
            'order_id' => 12,
            'message'  => 'La commande a mis plus de 45 minutes.',
            'status'   => 'en_attente',
        ]);

        // 1. L'employé (ID 2) formule une réponse
        $complaint->recordReply(employeeId: 2, reply: 'Désolé pour le retard, nous offrons une boisson.');
        $this->assertSame('repondue', $complaint->status);
        $this->assertSame(2, $complaint->replied_by);

        // 2. Le gérant (ID 1) valide le règlement
        $complaint->validateResolution(managerId: 1);
        $this->assertSame('validee', $complaint->status);
        $this->assertSame(1, $complaint->validated_by);
    }
}
