@extends('layouts.app')

@section('title', 'Tambah Asset Baru')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Assets', 'title' => 'Create Asset & Items'])

    <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
            {{-- Left Side: Master Info --}}
            <div class="lg:col-span-8 col-span-1">
                <div class="card mb-6">
                    <div class="card-body">
                        <h6 class="mb-4 card-title text-base flex items-center gap-2">
                            <i class="size-4 text-primary" data-lucide="info"></i> Informasi Katalog (Master)
                        </h6>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="name">Nama Barang</label>
                                <input class="form-input @error('name') border-danger @enderror" id="name" name="name" placeholder="Contoh: Laptop Lenovo Thinkpad" type="text" value="{{ old('name') }}" required />
                                @error('name')
                                    <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="asset_code">Master Code</label>
                                <input class="form-input @error('asset_code') border-danger @enderror" id="asset_code" name="asset_code" placeholder="AST-..." type="text" value="{{ old('asset_code') }}" />
                                <p class="mt-1 text-default-400 text-[10px] italic">Kode unik untuk model/katalog barang ini.</p>
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="category_id">Kategori</label>
                                <select class="form-input" id="category_id" name="category_id" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="uom_id">Satuan (UoM)</label>
                                <select class="form-input" id="uom_id" name="uom_id" required>
                                    @foreach($uoms as $uom)
                                        <option value="{{ $uom->id }}" {{ old('uom_id', 1) == $uom->id ? 'selected' : '' }}>{{ $uom->name }} ({{ $uom->symbol }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="font-medium text-default-800 text-sm mb-2 inline-block" for="notes">Deskripsi Katalog</label>
                            <textarea class="form-input" id="notes" name="notes" placeholder="Spesifikasi teknis, merek, model, dll..." rows="3">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header border-b border-default-200">
                        <div class="flex justify-between items-center">
                            <h6 class="card-title text-base flex items-center gap-2">
                                <i class="size-4 text-primary" data-lucide="layers"></i> Registrasi Unit Fisik
                            </h6>
                            <div class="flex items-center gap-3">
                                <label class="text-sm font-semibold text-default-700 whitespace-nowrap" for="gen-qty">Generate Unit:</label>
                                <div class="flex shadow-sm rounded-md">
                                    <input type="number" id="gen-qty" class="form-input form-input-sm w-20 rounded-e-none border-e-0 focus:ring-primary focus:border-primary z-10" value="1" min="1" max="50">
                                    <button type="button" id="btn-generate" class="btn btn-sm bg-primary hover:bg-primary-600 text-white rounded-s-none z-10 transition-colors">Tambah</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-default-200" id="items-table">
                                <thead class="bg-default-50">
                                    <tr>
                                        <th class="px-4 py-2 text-start text-xs font-bold text-default-600 uppercase min-w-[150px]">Barcode/Item Code</th>
                                        <th class="px-4 py-2 text-start text-xs font-bold text-default-600 uppercase min-w-[180px]">Serial Number</th>
                                        <th class="px-4 py-2 text-start text-xs font-bold text-default-600 uppercase min-w-[200px]">Lokasi</th>
                                        <th class="px-4 py-2 text-start text-xs font-bold text-default-600 uppercase min-w-[120px]">Kondisi</th>
                                        <th class="px-4 py-2 text-start text-xs font-bold text-default-600 uppercase min-w-[130px]">Fiskal (Tax)</th>
                                        <th class="px-4 py-2 text-start text-xs font-bold text-default-600 uppercase min-w-[150px]">Nilai Sisa</th>
                                        <th class="px-4 py-2 text-start text-xs font-bold text-default-600 uppercase min-w-[110px]">Umur (Bln)</th>
                                        <th class="px-4 py-2 text-center text-xs font-bold text-default-600 uppercase w-10"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-default-200" id="items-body">
                                    {{-- Baris pertama default --}}
                                    <tr class="item-row">
                                        <td class="px-4 py-3">
                                            <input type="text" name="items[0][item_code]" class="form-input form-input-sm font-mono text-primary font-bold" placeholder="Auto-gen" readonly>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" name="items[0][serial_number]" class="form-input form-input-sm" placeholder="SN Pabrik">
                                        </td>
                                        <td class="px-4 py-3">
                                            <select name="items[0][location_id]" class="form-input form-input-sm" required>
                                                @foreach($locations as $location)
                                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-4 py-3">
                                            <select name="items[0][condition]" class="form-input form-input-sm">
                                                <option value="Good">Good</option>
                                                <option value="New">New</option>
                                                <option value="Fair">Fair</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3">
                                            <select name="items[0][fiscal_group]" class="form-input form-input-sm">
                                                <option value="">Default</option>
                                                @foreach(\App\Models\AssetItem::FISCAL_GROUPS as $group => $months)
                                                    <option value="{{ $group }}">{{ $group }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" name="items[0][residual_value]" class="form-input form-input-sm residual-value-input" placeholder="0" value="0">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" name="items[0][useful_life_months]" class="form-input form-input-sm useful-life-input" placeholder="Bulan" value="0">
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button type="button" class="text-danger hover:text-danger-700 remove-row" title="Hapus Unit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="mt-4 text-xs text-default-500 italic">
                            * Item Code akan di-generate otomatis oleh sistem berdasarkan pola kode kategori dan nomor urut.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Right Side: Photo & Submit --}}
            <div class="lg:col-span-4 col-span-1">
                <div class="card mb-6">
                    <div class="card-body">
                        <h6 class="mb-4 card-title text-base flex items-center gap-2">
                            <i class="size-4 text-primary" data-lucide="image"></i> Foto Katalog
                        </h6>
                        <label for="images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-default-300 border-dashed rounded-lg cursor-pointer bg-default-50 hover:bg-default-100 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="size-8 text-default-400 mb-2" data-lucide="upload-cloud"></i>
                                <p class="text-xs text-default-500 font-semibold">Klik untuk Upload</p>
                            </div>
                            <input id="images" name="images[]" type="file" class="hidden" multiple accept="image/*" />
                        </label>
                        <div id="image-preview" class="grid grid-cols-2 gap-2 mt-4"></div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-4 card-title text-base flex items-center gap-2">
                            <i class="size-4 text-primary" data-lucide="dollar-sign"></i> Info Pembelian (General)
                        </h6>
                        <div class="mb-4">
                            <label class="text-sm font-medium text-default-800 mb-1 block">Harga Per Unit (Est)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-default-500">Rp</span>
                                <input type="number" name="price" class="form-input ps-10" placeholder="0" required>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="text-sm font-medium text-default-800 mb-1 block">Tanggal Beli</label>
                            <input type="date" name="purchase_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="grid grid-cols-1 gap-2">
                            <button type="submit" class="btn bg-primary text-white w-full py-3 font-bold uppercase tracking-widest">Simpan Master & Unit</button>
                            <a href="{{ route('assets.index') }}" class="btn border-default-200 text-default-600 w-full">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const itemsBody = document.getElementById('items-body');
        const btnGenerate = document.getElementById('btn-generate');
        const genQtyInput = document.getElementById('gen-qty');
        const categorySelect = document.getElementById('category_id');
        let rowIndex = 1;

        // Data category for default useful life and residual percentage
        const categoryDefaults = {
            @foreach($categories as $category)
                "{{ $category->id }}": {
                    "useful_life": "{{ $category->default_useful_life_months ?? 0 }}",
                    "residual_percent": "{{ $category->default_residual_percentage ?? 0 }}"
                },
            @endforeach
        };

        const priceInput = document.getElementsByName('price')[0];

        function getCategoryData() {
            const catId = categorySelect.value;
            return categoryDefaults[catId] || { useful_life: 0, residual_percent: 0 };
        }

        function updateAllItemsDefaults() {
            const catData = getCategoryData();
            const price = parseFloat(priceInput.value) || 0;
            const residualValue = Math.round(price * (catData.residual_percent / 100));

            document.querySelectorAll('.useful-life-input').forEach(input => {
                input.value = catData.useful_life;
            });
            document.querySelectorAll('.residual-value-input').forEach(input => {
                input.value = residualValue;
            });
        }

        // Update when category or price changes
        categorySelect.addEventListener('change', updateAllItemsDefaults);
        priceInput.addEventListener('input', updateAllItemsDefaults);

        // Function to create a new row
        function createRow(index, defaultUsefulLife = 0, defaultResidualValue = 0) {
            const tr = document.createElement('tr');
            tr.className = 'item-row';
            tr.innerHTML = `
                <td class="px-4 py-3">
                    <input type="text" name="items[${index}][item_code]" class="form-input form-input-sm font-mono text-primary font-bold" placeholder="Auto-gen" readonly>
                </td>
                <td class="px-4 py-3">
                    <input type="text" name="items[${index}][serial_number]" class="form-input form-input-sm" placeholder="SN Pabrik">
                </td>
                <td class="px-4 py-3">
                    <select name="items[${index}][location_id]" class="form-input form-input-sm" required>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="px-4 py-3">
                    <select name="items[${index}][condition]" class="form-input form-input-sm">
                        <option value="Good">Good</option>
                        <option value="New">New</option>
                        <option value="Fair">Fair</option>
                    </select>
                </td>
                <td class="px-4 py-3">
                    <select name="items[${index}][fiscal_group]" class="form-input form-input-sm">
                        <option value="">Default</option>
                        @foreach(\App\Models\AssetItem::FISCAL_GROUPS as $group => $months)
                            <option value="{{ $group }}">{{ $group }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="items[${index}][residual_value]" class="form-input form-input-sm residual-value-input" placeholder="0" value="${defaultResidualValue}">
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="items[${index}][useful_life_months]" class="form-input form-input-sm useful-life-input" placeholder="Bulan" value="${defaultUsefulLife}">
                </td>
                <td class="px-4 py-3 text-center">
                    <button type="button" class="text-danger hover:text-danger-700 remove-row" title="Hapus Unit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </td>
            `;
            return tr;
        }

        // Generate Rows
        btnGenerate.addEventListener('click', function () {
            const qty = parseInt(genQtyInput.value);
            const catData = getCategoryData();
            const price = parseFloat(priceInput.value) || 0;
            const residualValue = Math.round(price * (catData.residual_percent / 100));

            for (let i = 0; i < qty; i++) {
                itemsBody.appendChild(createRow(rowIndex, catData.useful_life, residualValue));
                rowIndex++;
            }
        });

        // Remove Row
        itemsBody.addEventListener('click', function (e) {
            if (e.target.closest('.remove-row')) {
                const row = e.target.closest('.item-row');
                if (itemsBody.querySelectorAll('.item-row').length > 1) {
                    row.remove();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Minimal harus ada 1 unit fisik yang didaftarkan.',
                        confirmButtonColor: '#3e60d5'
                    });
                }
            }
        });

        // Image Preview
        const imageInput = document.getElementById('images');
        const previewDiv = document.getElementById('image-preview');
        imageInput.addEventListener('change', function() {
            previewDiv.innerHTML = '';
            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full aspect-square object-cover rounded border border-default-200';
                    previewDiv.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        });
    });
</script>
@endpush
