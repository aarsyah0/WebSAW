<?php

namespace Tests\Unit;

use App\Models\Criteria;
use App\Models\Product;
use App\Services\ToyRecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToyRecommendationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ToyRecommendationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ToyRecommendationService;
    }

    public function test_returns_empty_when_no_products_match_filter(): void
    {
        Product::create(['name' => 'P1', 'price' => 50000, 'stock' => 0, 'age_range' => '3-6']);
        $result = $this->service->getRecommendations([
            'age_min' => 3,
            'age_max' => 6,
            'budget_min' => 0,
            'budget_max' => 100000,
            'priorities' => ['harga' => 3, 'kualitas' => 3, 'keamanan' => 3, 'edukasi' => 3, 'popularitas' => 3],
        ]);
        $this->assertCount(0, $result);
    }

    public function test_returns_top_5_recommendations_with_scores(): void
    {
        Criteria::insert([
            ['name' => 'Harga', 'type' => 'cost', 'weight_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kualitas', 'type' => 'benefit', 'weight_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keamanan', 'type' => 'benefit', 'weight_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Edukasi', 'type' => 'benefit', 'weight_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Popularitas', 'type' => 'benefit', 'weight_order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $criterias = Criteria::all();
        for ($i = 0; $i < 6; $i++) {
            $p = Product::create([
                'name' => "Product $i",
                'price' => 100000 + $i * 20000,
                'stock' => 10,
                'age_range' => '3-6',
            ]);
            $sync = [];
            foreach ($criterias as $c) {
                $sync[$c->id] = ['value' => $c->name === 'Harga' ? $p->price : 4];
            }
            $p->criterias()->sync($sync);
        }
        $result = $this->service->getRecommendations([
            'age_min' => 3,
            'age_max' => 6,
            'budget_min' => 0,
            'budget_max' => 500000,
            'priorities' => ['harga' => 5, 'kualitas' => 5, 'keamanan' => 5, 'edukasi' => 5, 'popularitas' => 5],
        ]);
        $this->assertLessThanOrEqual(5, $result->count());
        foreach ($result as $item) {
            $this->assertArrayHasKey('rank', $item);
            $this->assertArrayHasKey('product', $item);
            $this->assertArrayHasKey('score', $item);
            $this->assertArrayHasKey('explanation', $item);
        }
    }
}
