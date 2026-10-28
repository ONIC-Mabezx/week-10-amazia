<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderInvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_an_order_and_download_its_invoice(): void
    {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'Kopi Sabu',
            'price' => 18000.50,
            'stock' => 5,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)
            ->post(route('products.orders.store', $product), ['quantity' => 2]);

        $order = Order::query()->firstOrFail();
        $response->assertRedirect(route('orders.invoice', $order));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 3,
        ]);
        $this->assertSame('36001.00', $order->total);

        $invoiceResponse = $this->actingAs($user)
            ->get(route('orders.invoice', $order))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->assertStringStartsWith('%PDF', $invoiceResponse->getContent());
    }

    public function test_order_cannot_exceed_available_stock(): void
    {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'Kopi Sabu',
            'price' => 18000,
            'stock' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->from(route('products.show', $product))
            ->post(route('products.orders.store', $product), ['quantity' => 2])
            ->assertSessionHasErrors('quantity');

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 1,
        ]);
    }

    public function test_user_cannot_download_another_users_invoice(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::create([
            'name' => 'Kopi Sabu',
            'price' => 18000,
            'stock' => 0,
            'is_active' => true,
        ]);
        $order = Order::create([
            'invoice_number' => 'SM-20261028-000001',
            'user_id' => $owner->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 1,
            'unit_price' => 18000,
            'total' => 18000,
            'status' => 'unpaid',
        ]);

        $this->actingAs($otherUser)
            ->get(route('orders.invoice', $order))
            ->assertNotFound();
    }
}
