@extends('layouts.app')

@section('title', 'Tambah Asset Baru')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Assets', 'title' => 'Create Asset'])

    <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
        <div class="lg:col-span-9 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title text-base">Informasi Utama Asset</h6>

                    <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="name">Asset Title</label>
                                <input class="form-input @error('name') border-danger @enderror" id="name" name="name" placeholder="Enter asset name" type="text" value="{{ old('name') }}" required />
                                @error('name')
                                    <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="asset_code">Asset Code</label>
                                <input class="form-input @error('asset_code') border-danger @enderror" id="asset_code" name="asset_code" placeholder="Auto-generated if empty" type="text" value="{{ old('asset_code') }}" />
                                <p class="mt-1 text-default-400 text-xs italic">System will generate unique code if left blank.</p>
                                @error('asset_code')
                                    <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="category_id">Category</label>
                                <select class="form-input @error('category_id') border-danger @enderror" id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="uom_id">Unit of Measurement (Satuan)</label>
                                <select class="form-input @error('uom_id') border-danger @enderror" id="uom_id" name="uom_id" required>
                                    <option value="">Select UoM</option>
                                    @foreach($uoms as $uom)
                                        <option value="{{ $uom->id }}" {{ old('uom_id') == $uom->id ? 'selected' : '' }}>{{ $uom->name }} ({{ $uom->symbol }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="purchase_date">Purchase Date</label>
                                <input class="form-input" id="purchase_date" name="purchase_date" type="date" value="{{ old('purchase_date') }}" required />
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="price">Harga Satuan (Unit Price)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-default-500 font-semibold">Rp</span>
                                    <input class="form-input ps-10" id="price" name="price" placeholder="Masukkan harga per 1 unit" type="number" value="{{ old('price') }}" required />
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-2 mb-6">
                            <label class="font-medium text-default-800 text-sm" for="notes">Description / Internal Notes</label>
                            <textarea class="form-input" id="notes" name="notes" placeholder="Enter additional asset details..." rows="3">{{ old('notes') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 mb-8">
                            <label class="font-medium text-default-800 text-sm mb-2">Asset Images (Max 4 images, 2MB each)</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="images" class="flex flex-col items-center justify-center w-full h-40 border-2 border-default-300 border-dashed rounded-lg cursor-pointer bg-default-50 hover:bg-default-100 transition-all">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="size-10 text-default-400 mb-3" data-lucide="image-plus"></i>
                                        <p class="mb-2 text-sm text-default-500 font-semibold text-center">Klik untuk upload atau tarik gambar ke sini</p>
                                        <p class="text-xs text-default-400 text-center uppercase tracking-wider">PNG, JPG atau JPEG (MAX. 2MB per foto)</p>
                                    </div>
                                    <input id="images" name="images[]" type="file" class="hidden" multiple accept="image/*" />
                                </label>
                            </div>
                            <div id="image-preview-container" class="grid grid-cols-4 gap-4 mt-4">
                                {{-- Preview images will be injected here --}}
                            </div>
                            @error('images')
                                <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="border-t border-default-200 pt-6">
                            <div class="flex justify-between items-center mb-4">
                                <h6 class="card-title text-base">Distribusi Stok & Lokasi</h6>
                                <p class="text-xs text-default-500 font-medium italic">*Anda bisa membagi aset ini ke beberapa lokasi sekaligus</p>
                            </div>
                            
                            <div id="distribution-container">
                                <div class="distribution-row grid lg:grid-cols-12 grid-cols-1 gap-4 mb-3 items-end bg-default-50 p-4 rounded-md border border-default-200">
                                    <div class="lg:col-span-5">
                                        <label class="text-xs font-semibold text-default-600 mb-1 block">Lokasi Penyimpanan</label>
                                        <select name="distributions[0][location_id]" class="form-input form-input-sm" required>
                                            @foreach($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->full_path }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="lg:col-span-3">
                                        <label class="text-xs font-semibold text-default-600 mb-1 block">Kuantitas (Qty)</label>
                                        <input type="number" name="distributions[0][quantity]" class="form-input form-input-sm" value="1" min="1" required>
                                    </div>
                                    <div class="lg:col-span-3">
                                        <label class="text-xs font-semibold text-default-600 mb-1 block">Status Unit</label>
                                        <select name="distributions[0][status]" class="form-input form-input-sm">
                                            <option value="Available">Available</option>
                                            <option value="Deployed">Deployed</option>
                                            <option value="Maintenance">Maintenance</option>
                                            <option value="Broken">Broken</option>
                                        </select>
                                    </div>
                                    <div class="lg:col-span-1 flex justify-center pb-1.5">
                                        <button type="button" class="text-danger hover:text-danger-700 transition-all remove-row disabled:opacity-30" disabled>
                                            <i data-lucide="trash-2" class="size-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" id="add-distribution" class="btn btn-sm border-dashed border-primary/50 text-primary hover:bg-primary/5 mt-2 w-full">
                                <i data-lucide="plus" class="size-4 me-1"></i> Tambah Distribusi Lokasi Lainnya
                            </button>
                        </div>

                        <div class="mt-6 flex gap-3 md:justify-end border-t border-default-200 pt-5">
                            <a href="{{ route('assets.index') }}" class="btn border-default-200 text-default-600 hover:bg-default-100">Cancel</a>
                            <button type="submit" class="text-white btn bg-primary px-10">Create Asset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 col-span-1">
            <div class="sticky top-24">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-4 card-title">Asset Card Preview</h6>
                        <div class="px-5 py-8 rounded-md bg-info/10 flex items-center justify-center">
                            <i class="size-24 text-info/30" data-lucide="package"></i>
                        </div>
                        <div class="mt-4">
                            <h5 class="mb-1 text-default-800 font-semibold text-lg" id="preview-name">New Asset</h5>
                            <p class="text-primary font-bold text-sm">#CODE-PREVIEW</p>
                            <div class="mt-3 flex items-center gap-2">
                                <span class="bg-success/15 text-success px-2 py-0.5 rounded text-xs font-medium">Available</span>
                            </div>
                        </div>
                        <div class="mt-5 pt-5 border-t border-default-200">
                            <p class="text-default-500 text-xs italic leading-relaxed">
                                Tip: Pastikan semua data perolehan diisi dengan benar untuk pelaporan depresiasi aset yang akurat.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('distribution-container');
        const addButton = document.getElementById('add-distribution');
        let rowIndex = 1;

        // Add row
        addButton.addEventListener('click', function () {
            const firstRow = container.querySelector('.distribution-row');
            const newRow = firstRow.cloneNode(true);
            
            // Update names
            newRow.querySelectorAll('select, input').forEach(el => {
                const name = el.getAttribute('name');
                if (name) {
                    el.setAttribute('name', name.replace('[0]', '[' + rowIndex + ']'));
                }
                if (el.tagName === 'INPUT') el.value = 1;
            });

            // Enable delete button
            const removeBtn = newRow.querySelector('.remove-row');
            removeBtn.removeAttribute('disabled');
            removeBtn.classList.remove('opacity-30');

            container.appendChild(newRow);
            rowIndex++;
            
            // Re-init lucide icons if any
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });

        // Remove row
        container.addEventListener('click', function (e) {
            if (e.target.closest('.remove-row')) {
                const row = e.target.closest('.distribution-row');
                if (container.querySelectorAll('.distribution-row').length > 1) {
                    row.remove();
                }
            }
        });

        // Preview Sync
        const nameInput = document.getElementById('name');
        const previewName = document.getElementById('preview-name');
        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || 'New Asset';
        });

        // Image Preview Logic
        const imageInput = document.getElementById('images');
        const previewContainer = document.getElementById('image-preview-container');

        imageInput.addEventListener('change', function() {
            previewContainer.innerHTML = '';
            const files = Array.from(this.files).slice(0, 4); // Limit to 4
            
            if (this.files.length > 4) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Maksimal',
                    text: 'Hanya 4 gambar pertama yang akan diunggah.',
                    confirmButtonColor: '#4f46e5',
                });
            }

            files.forEach((file, index) => {
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar',
                        text: `Gambar "${file.name}" melebihi batas 2MB.`,
                        confirmButtonColor: '#4f46e5',
                    });
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group rounded-lg overflow-hidden border border-default-200 shadow-sm aspect-square bg-white';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                            <span class="text-white text-[10px] font-bold uppercase tracking-widest">Preview ${index + 1}</span>
                        </div>
                    `;
                    previewContainer.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        });
    });
</script>
@endpush
@endsection
