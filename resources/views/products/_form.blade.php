<div class="row">

    <!-- NAMA -->

    <div class="col-md-8 mb-4">

        <label class="form-label">

            <i class="bi bi-box me-1"></i>

            Nama Product

        </label>

        <input
            type="text"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $product->name ?? '') }}"
            placeholder="Contoh: Gula Lempeng"
        >

        @error('name')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    <!-- KATEGORI -->

    <div class="col-md-4 mb-4">

        <label class="form-label">

            <i class="bi bi-tag me-1"></i>

            Kategori

        </label>

        <input
            type="text"
            name="category"
            class="form-control @error('category') is-invalid @enderror"
            value="{{ old('category', $product->category ?? '') }}"
            placeholder="Makanan"
        >

        @error('category')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    <!-- DESKRIPSI -->

    <div class="col-12 mb-4">

        <label class="form-label">

            <i class="bi bi-card-text me-1"></i>

            Deskripsi

        </label>

        <textarea
            name="description"
            rows="5"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Tuliskan deskripsi product..."
        >{{ old('description', $product->description ?? '') }}</textarea>

        @error('description')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    <!-- HARGA -->

    <div class="col-md-6 mb-4">

        <label class="form-label">

            <i class="bi bi-cash-stack me-1"></i>

            Harga

        </label>

        <div class="input-group">

            <span class="input-group-text">
                Rp
            </span>

            <input
                type="number"
                name="price"
                min="0"
                class="form-control @error('price') is-invalid @enderror"
                value="{{ old('price', $product->price ?? '') }}"
                placeholder="5000"
            >

        </div>

        @error('price')

            <div class="text-danger small mt-1">
                {{ $message }}
            </div>

        @enderror

    </div>


    <!-- STOCK -->

    <div class="col-md-6 mb-4">

        <label class="form-label">

            <i class="bi bi-stack me-1"></i>

            Stock

        </label>

        <input
            type="number"
            name="stock"
            min="0"
            class="form-control @error('stock') is-invalid @enderror"
            value="{{ old('stock', $product->stock ?? 0) }}"
            placeholder="10"
        >

        @error('stock')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    <!-- FOTO -->

    <div class="col-12 mb-4">

        <label class="form-label">

            <i class="bi bi-image me-1"></i>

            Foto Product

        </label>


        <div
            style="
                border: 2px dashed #cbd5e1;
                border-radius: 14px;
                padding: 25px;
                background: #f8fafc;
            "
        >

            <input
                type="file"
                name="image"
                class="form-control @error('image') is-invalid @enderror"
                accept=".jpg,.jpeg,.png,.webp"
            >


            <small class="text-muted d-block mt-2">

                <i class="bi bi-info-circle me-1"></i>

                JPG, JPEG, PNG atau WEBP — maksimal 2 MB. Foto akan dipotong menjadi persegi, diberi watermark SabuMarket, dan disimpan sebagai WEBP.

            </small>

        </div>

        <div class="mt-3">
            <label for="image_rotation" class="form-label">Rotasi foto</label>
            <select
                name="image_rotation"
                id="image_rotation"
                class="form-select @error('image_rotation') is-invalid @enderror"
            >
                @foreach([0, 90, 180, 270] as $rotation)
                    <option
                        value="{{ $rotation }}"
                        @selected((int) old('image_rotation', 0) === $rotation)
                    >
                        {{ $rotation }}&deg;
                    </option>
                @endforeach
            </select>
            @error('image_rotation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        @error('image')

            <div class="text-danger small mt-1">
                {{ $message }}
            </div>

        @enderror

    </div>


    <!-- FOTO LAMA -->

    @if(isset($product) && $product->image)

        <div class="col-12 mb-4">

            <label class="form-label">
                Foto Saat Ini
            </label>

            <div>

                <img
                    src="{{ asset('storage/' . $product->image) }}"
                    alt="{{ $product->name }}"
                    style="
                        width: 130px;
                        height: 130px;
                        object-fit: cover;
                        border-radius: 14px;
                        border: 3px solid #e2e8f0;
                    "
                >

            </div>

        </div>

    @endif


    <!-- STATUS -->

    <div class="col-12">

        <div
            style="
                background: #f8fafc;
                padding: 15px 18px;
                border-radius: 12px;
                border: 1px solid #e2e8f0;
            "
        >

            <div class="form-check form-switch">

                <input
                    class="form-check-input"
                    type="checkbox"
                    role="switch"
                    name="is_active"
                    value="1"
                    id="is_active"
                    {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                >

                <label
                    class="form-check-label"
                    for="is_active"
                >

                    <strong>
                        Product Aktif
                    </strong>

                    <br>

                    <small class="text-muted">
                        Product akan ditampilkan sebagai product aktif.
                    </small>

                </label>

            </div>

        </div>

    </div>

</div>