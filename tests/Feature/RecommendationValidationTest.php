<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommendationValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_recommendation_requires_age_and_budget(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)
            ->post(route('recommendation.result'), [])
            ->assertSessionHasErrors(['age_min', 'age_max', 'budget_min', 'budget_max']);
    }

    public function test_recommendation_accepts_valid_input(): void
    {
        $this->seed([\Database\Seeders\CriteriaSeeder::class, \Database\Seeders\ProductSeeder::class, \Database\Seeders\ProductCriteriaSeeder::class]);
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)
            ->post(route('recommendation.result'), [
                'age_min' => 3,
                'age_max' => 6,
                'budget_min' => 50000,
                'budget_max' => 300000,
                'priorities' => ['harga' => 3, 'kualitas' => 4, 'keamanan' => 3, 'edukasi' => 4, 'popularitas' => 3],
            ])
            ->assertOk();
    }
}
