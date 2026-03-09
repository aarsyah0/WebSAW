<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_checkout(): void
    {
        $this->get(route('checkout.show'))->assertRedirect(route('login'));
    }

    public function test_user_can_checkout_and_transaction_created(): void
    {
        $this->seed(\Database\Seeders\CriteriaSeeder::class);
        $user = User::factory()->create(['role' => 'user']);
        $product = Product::create([
            'name' => 'Test Toy',
            'price' => 100000,
            'stock' => 5,
            'age_range' => '3-6',
        ]);
        $user->carts()->create(['product_id' => $product->id, 'quantity' => 2]);

        $this->actingAs($user)
            ->post(route('checkout.process'), [
                'address' => 'Jl. Test No. 1',
                'phone' => '08123456789',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'total' => 200000,
        ]);
        $this->assertEquals(3, $product->fresh()->stock);
        $this->assertCount(0, $user->carts()->get());
    }
}
