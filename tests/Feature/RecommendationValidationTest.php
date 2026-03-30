<?php

namespace Tests\Feature;

use App\Models\Criteria;
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
        $priorities = [];
        $byName = [
            'Harga' => 3,
            'Kualitas' => 4,
            'Keamanan' => 3,
            'Edukasi' => 4,
            'Popularitas' => 3,
        ];
        foreach (Criteria::orderBy('weight_order')->get() as $c) {
            $priorities[$c->id] = $byName[$c->name] ?? 3;
        }
        $this->actingAs($user)
            ->post(route('recommendation.result'), [
                'age_min' => 3,
                'age_max' => 6,
                'budget_min' => 50000,
                'budget_max' => 300000,
                'priorities' => $priorities,
            ])
            ->assertOk();
    }
}
