<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductImageProcessor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::query()
            ->where('is_active', true)
            ->latest()
            ->paginate(15);

        return response()->json($products);
    }

    public function store(Request $request, ProductImageProcessor $imageProcessor): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image_rotation' => ['sometimes', 'integer', 'in:0,90,180,270'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $imageProcessor->store(
                $request->file('image'),
                (int) ($data['image_rotation'] ?? 0)
            );
        }

        unset($data['image_rotation']);

        $product = Product::create($data);
        $product->refresh();

        return response()->json($product, 201);
    }

    public function show(string $id): JsonResponse
    {
        $product = Product::query()
            ->where('is_active', true)
            ->findOrFail($id);

        return response()->json($product);
    }

    public function update(Request $request, Product $product, ProductImageProcessor $imageProcessor): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'category' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image_rotation' => ['sometimes', 'integer', 'in:0,90,180,270'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $previousImage = $product->image;

        if ($request->hasFile('image')) {
            $data['image'] = $imageProcessor->store(
                $request->file('image'),
                (int) ($data['image_rotation'] ?? 0)
            );
        }

        unset($data['image_rotation']);

        $product->update($data);

        if ($request->hasFile('image') && $previousImage) {
            Storage::disk('public')->delete($previousImage);
        }

        return response()->json($product);
    }

    public function destroy(Product $product): JsonResponse
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
