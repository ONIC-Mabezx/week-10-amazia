<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $order = DB::transaction(function () use ($request, $product, $data): Order {
            $lockedProduct = Product::query()
                ->lockForUpdate()
                ->findOrFail($product->id);

            if (! $lockedProduct->is_active) {
                throw ValidationException::withMessages([
                    'quantity' => 'Produk ini sedang tidak tersedia.',
                ]);
            }

            if ($data['quantity'] > $lockedProduct->stock) {
                throw ValidationException::withMessages([
                    'quantity' => 'Jumlah pesanan melebihi stok yang tersedia.',
                ]);
            }

            $unitPrice = (string) $lockedProduct->price;
            [$whole, $fraction] = array_pad(explode('.', $unitPrice, 2), 2, '0');
            $unitPriceInCents = ((int) $whole * 100) + (int) str_pad($fraction, 2, '0');

            if ($unitPriceInCents > 0 && $data['quantity'] > intdiv(999_999_999_999, $unitPriceInCents)) {
                throw ValidationException::withMessages([
                    'quantity' => 'Total pesanan melebihi batas nilai invoice.',
                ]);
            }

            $totalInCents = $unitPriceInCents * $data['quantity'];
            $order = Order::create([
                'invoice_number' => 'TMP-'.Str::uuid(),
                'user_id' => $request->user()->id,
                'product_id' => $lockedProduct->id,
                'product_name' => $lockedProduct->name,
                'quantity' => $data['quantity'],
                'unit_price' => $unitPrice,
                'total' => number_format($totalInCents / 100, 2, '.', ''),
                'status' => 'unpaid',
            ]);

            $order->update([
                'invoice_number' => sprintf('SM-%s-%06d', now()->format('Ymd'), $order->id),
            ]);
            $lockedProduct->decrement('stock', $data['quantity']);

            return $order;
        });

        return redirect()->route('orders.invoice', $order);
    }

    public function invoice(Request $request, int $order)
    {
        $order = Order::query()
            ->with('user')
            ->where('user_id', $request->user()->id)
            ->findOrFail($order);

        return Pdf::loadView('orders.invoice', compact('order'))
            ->setPaper('a4')
            ->download($order->invoice_number.'.pdf');
    }
}
