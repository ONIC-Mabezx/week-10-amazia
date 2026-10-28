<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Typography\Font;
use RuntimeException;

class ProductImageProcessor
{
    public function store(UploadedFile $file, int $rotation = 0): string
    {
        if (! extension_loaded('gd') || ! function_exists('imagewebp')) {
            throw ValidationException::withMessages([
                'image' => 'Aktifkan ekstensi GD dengan dukungan WebP pada PHP untuk memproses foto produk.',
            ]);
        }

        $image = (new ImageManager(new Driver))
            ->read($file->getRealPath())
            ->orient()
            ->rotate($rotation)
            ->cover(1200, 1200);

        $watermark = new Font('5');
        $watermark->setColor('ffffff');
        $watermark->setAlignment('right');
        $watermark->setValignment('bottom');
        $watermark->setStrokeWidth(1);
        $watermark->setStrokeColor('0f172a');
        $image->text('SabuMarket', 1160, 1160, $watermark);

        $path = 'products/'.Str::uuid().'.webp';

        if (! Storage::disk('public')->put($path, $image->toWebp(85)->toString())) {
            throw new RuntimeException('Foto produk gagal disimpan ke penyimpanan publik.');
        }

        return $path;
    }
}
