@extends('layouts.app')

@section('title', 'Edit Asset: ' . $asset->name)

@section('content')
    @include('layouts.partials/page-title', [
        'subtitle' => 'Assets', 
        'title' => 'Edit Asset',
        'breadcrumbs' => [
            ['label' => 'Asset List', 'url' => route('assets.index')],
            ['label' => 'Edit Asset', 'url' => null],
        ]
    ])

    <form action="{{ route('assets.update', $asset) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
            {{-- Left Side: Master Info --}}
            <div class="lg:col-span-9 col-span-1">
                <div class="card mb-6">
                    <div class="card-body">
                        <h6 class="mb-4 card-title text-base flex items-center gap-2">
                            <i class="size-4 text-primary" data-lucide="info"></i> Catalog Information (Master)
                        </h6>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="name">Asset Name</label>
                                <input class="form-input" id="name" name="name" type="text" value="{{ old('name', $asset->name) }}" required />
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="asset_code">Master Code</label>
                                <input class="form-input" id="asset_code" name="asset_code" type="text" value="{{ old('asset_code', $asset->asset_code) }}" />
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-3 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="category_id">Category</label>
                                <select class="form-input" id="category_id" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $asset->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="brand">Brand / Model</label>
                                <input class="form-input" id="brand" name="brand" type="text" value="{{ old('brand', $asset->brand) }}" placeholder="e.g.: Apple, Dell, etc." />
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="uom_id">UOM</label>
                                <select class="form-input" id="uom_id" name="uom_id" required>
                                    @foreach($uoms as $uom)
                                        <option value="{{ $uom->id }}" {{ old('uom_id', $asset->uom_id) == $uom->id ? 'selected' : '' }}>{{ $uom->name }} ({{ $uom->symbol }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Unit Price (Estimated)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-default-500">Rp</span>
                                    <input type="number" name="price" class="form-input ps-10" value="{{ old('price', (int)$asset->price) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="font-medium text-default-800 text-sm mb-2 inline-block" for="notes">Catalog Description</label>
                            <textarea class="form-input" id="notes" name="notes" rows="4">{{ old('notes', $asset->notes) }}</textarea>
                        </div>

                        <div class="flex justify-end gap-3 mt-8">
                            <a href="{{ route('assets.show', $asset) }}" class="btn border-default-200 text-default-600 px-6">Cancel</a>
                            <button type="submit" class="text-white btn bg-primary px-10">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side: Photos --}}
            <div class="lg:col-span-3 col-span-1">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-4 card-title text-sm font-bold uppercase tracking-widest text-default-600">Asset Gallery</h6>
                        
                        <div class="grid grid-cols-1 gap-3 mb-6">
                            @foreach($asset->images as $image)
                                <div class="relative group aspect-square rounded-lg overflow-hidden border border-default-200">
                                    <img src="{{ $image->url }}" class="size-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                        <button type="button" onclick="deleteImage({{ $image->id }})" class="size-8 bg-danger text-white rounded-full flex items-center justify-center hover:bg-danger-600">
                                            <i class="size-4" data-lucide="trash-2"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach

                            @if($asset->images->count() < 4)
                                <label for="images" class="aspect-square flex flex-col items-center justify-center w-full border-2 border-default-300 border-dashed rounded-lg cursor-pointer bg-default-50 hover:bg-default-100 transition-all">
                                    <div class="flex flex-col items-center justify-center p-4 text-center">
                                        <i class="size-6 text-default-400 mb-2" data-lucide="upload-cloud"></i>
                                        <p class="text-[10px] text-default-500 font-semibold uppercase">Add Photo (Max {{ 4 - $asset->images->count() }})</p>
                                    </div>
                                    <input id="images" name="images[]" type="file" class="hidden" multiple accept="image/*" />
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="delete-image-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deleteImage(id) {
        Swal.fire({
            title: 'Delete Image?',
            text: "This image will be permanently removed from the system.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-image-form');
                form.action = `/assets/images/${id}`;
                form.submit();
            }
        });
    }
</script>
@endpush
