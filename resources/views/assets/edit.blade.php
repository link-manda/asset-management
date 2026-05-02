@extends('layouts.app')

@section('title', 'Edit Asset: ' . $asset->name)

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Assets', 'title' => 'Edit Asset'])

    <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
        <div class="lg:col-span-9 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title text-base">Perbarui Informasi Asset</h6>
                    
                    <form action="{{ route('assets.update', $asset) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="name">Asset Title</label>
                                <input class="form-input @error('name') border-danger @enderror" id="name" name="name" placeholder="Enter asset name" type="text" value="{{ old('name', $asset->name) }}" required />
                                @error('name')
                                    <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="asset_code">Asset Code</label>
                                <input class="form-input @error('asset_code') border-danger @enderror" id="asset_code" name="asset_code" placeholder="Asset Code" type="text" value="{{ old('asset_code', $asset->asset_code) }}" required />
                                @error('asset_code')
                                    <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="category_id">Category</label>
                                <select class="form-input @error('category_id') border-danger @enderror" id="category_id" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $asset->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="uom_id">Unit of Measurement (Satuan)</label>
                                <select class="form-input @error('uom_id') border-danger @enderror" id="uom_id" name="uom_id" required>
                                    @foreach($uoms as $uom)
                                        <option value="{{ $uom->id }}" {{ old('uom_id', $asset->uom_id) == $uom->id ? 'selected' : '' }}>{{ $uom->name }} ({{ $uom->symbol }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="price">Harga Satuan (Unit Price)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-default-500 font-semibold">Rp</span>
                                    <input class="form-input ps-10" id="price" name="price" placeholder="Masukkan harga per 1 unit" type="number" value="{{ old('price', $asset->price) }}" required />
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-2 mb-6">
                            <label class="font-medium text-default-800 text-sm" for="notes">Description / Internal Notes</label>
                            <textarea class="form-input" id="notes" name="notes" placeholder="Enter additional asset details..." rows="3">{{ old('notes', $asset->notes) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 mb-8">
                            <label class="font-medium text-default-800 text-sm mb-2">Asset Images (Max 4 images total)</label>
                            
                            @if($asset->images->count() > 0)
                                <div class="grid grid-cols-4 gap-4 mb-4">
                                    @foreach($asset->images as $image)
                                        <div class="relative group rounded-lg overflow-hidden border border-default-200 aspect-square">
                                            <img src="{{ $image->url }}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center gap-2">
                                                <button type="button" onclick="confirmDeleteImage({{ $image->id }})" class="size-8 bg-danger text-white rounded-full flex items-center justify-center hover:bg-danger-600 transition-colors">
                                                    <i class="size-4" data-lucide="trash-2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex items-center justify-center w-full">
                                <label for="images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-default-300 border-dashed rounded-lg cursor-pointer bg-default-50 hover:bg-default-100 transition-all">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="size-8 text-default-400 mb-2" data-lucide="image-plus"></i>
                                        <p class="text-xs text-default-500 font-semibold">Tambah Foto Baru (Max {{ 4 - $asset->images->count() }})</p>
                                    </div>
                                    <input id="images" name="images[]" type="file" class="hidden" multiple accept="image/*" {{ $asset->images->count() >= 4 ? 'disabled' : '' }} />
                                </label>
                            </div>
                            <div id="image-preview-container" class="grid grid-cols-4 gap-4 mt-4"></div>
                        </div>

                        {{-- Distribution Section Removed: Manage physical units individually in the Asset Show view to preserve history --}}

                        <div class="mt-10 flex gap-3 md:justify-end border-t border-default-200 pt-5">
                            <a href="{{ route('assets.show', $asset) }}" class="btn border-default-200 text-default-600 hover:bg-default-100">Batal</a>
                            <button type="submit" class="text-white btn bg-primary px-10">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 col-span-1">
            <div class="sticky top-24">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-4 card-title">Asset Overview</h6>
                        <div class="px-5 py-8 rounded-md bg-info/10 flex items-center justify-center">
                            <i class="size-24 text-info/30" data-lucide="package"></i>
                        </div>
                        <div class="mt-4">
                            <h5 class="mb-1 text-default-800 font-semibold text-lg" id="preview-name">{{ $asset->name }}</h5>
                            <p class="text-primary font-bold text-sm">#{{ $asset->asset_code }}</p>
                            <div class="mt-3">
                                <p class="text-xs text-default-500 font-medium uppercase tracking-wider">Total Inventory:</p>
                                <p class="text-xl font-black text-default-800">{{ $asset->total_quantity }} {{ $asset->uom?->symbol }}</p>
                            </div>
                        </div>
                        <div class="mt-5 pt-5 border-t border-default-200">
                            <p class="text-default-500 text-xs italic leading-relaxed">
                                Pastikan distribusi stok sesuai dengan fisik barang di gudang/lokasi terkait.
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

        const nameInput = document.getElementById('name');
        const previewName = document.getElementById('preview-name');
        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || 'New Asset';
        });

        // Image Preview Logic
        const imageInput = document.getElementById('images');
        const previewContainer = document.getElementById('image-preview-container');
        const existingCount = {{ $asset->images->count() }};

        if (imageInput) {
            imageInput.addEventListener('change', function() {
                previewContainer.innerHTML = '';
                const maxAllowed = 4 - existingCount;
                const files = Array.from(this.files).slice(0, maxAllowed);
                
                if (this.files.length > maxAllowed) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Batas Maksimal',
                        text: `Hanya ${maxAllowed} gambar pertama yang akan diunggah.`,
                        confirmButtonColor: '#4f46e5',
                    });
                }

                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative rounded-lg overflow-hidden border border-default-200 aspect-square bg-white';
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                        previewContainer.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            });
        }
    });

    function confirmDeleteImage(id) {
        Swal.fire({
            title: 'Hapus Gambar?',
            text: "Gambar ini akan dihapus permanen dari sistem.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit deletion via a hidden form or AJAX
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/assets/images/${id}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        })
    }
</script>
@endpush
@endsection
