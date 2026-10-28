<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageProcessingTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_upload_is_cropped_watermarked_and_saved_as_webp(): void
    {
        if (! extension_loaded('gd') || ! function_exists('imagewebp')) {
            $this->markTestSkipped('GD with WebP support is required for image processing.');
        }

        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('products.store'), [
                'name' => 'Kopi Sabu',
                'price' => 18000,
                'stock' => 3,
                'image_rotation' => 90,
                'image' => UploadedFile::fake()->image('kopi.jpg', 640, 480),
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('products.index'));

        $product = Product::query()->where('name', 'Kopi Sabu')->firstOrFail();
        $this->assertStringEndsWith('.webp', $product->image);

        $contents = Storage::disk('public')->get($product->image);
        $imageInfo = getimagesizefromstring($contents);
        $this->assertSame('image/webp', $imageInfo['mime']);
        $this->assertSame(1200, $imageInfo[0]);
        $this->assertSame(1200, $imageInfo[1]);
    }
}
