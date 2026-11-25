@extends('layouts.app')

@section('title', 'Edit Location: ' . $location->name)

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Locations', 'title' => 'Edit Location'])

    <div class="grid grid-cols-1 gap-6">
        <div class="card">
            <div class="card-header border-b border-default-200 bg-default-50/50">
                <h6 class="card-title">Edit Location Information</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('locations.update', $location) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="form-label text-sm font-medium text-default-700 mb-2 block" for="name">Location Name <span class="text-danger">*</span></label>
                            <input class="form-input w-full @error('name') border-danger @enderror" id="name" name="name" type="text" value="{{ old('name', $location->name) }}" required />
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label text-sm font-medium text-default-700 mb-2 block" for="parent_id">Parent Location</label>
                            <select name="parent_id" class="form-input w-full">
                                <option value="">- Set as Top Level Location -</option>
                                @foreach($allLocations as $parent)
                                    @if($parent->id != $location->id)
                                        <option value="{{ $parent->id }}" {{ old('parent_id', $location->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-default-400 italic">Select a parent if this location is a sub-area.</p>
                        </div>

                        <div>
                            <label class="form-label text-sm font-medium text-default-700 mb-2 block" for="address">Full Address <span class="text-danger">*</span></label>
                            <textarea class="form-input w-full @error('address') border-danger @enderror" id="address" name="address" rows="5" required>{{ old('address', $location->address) }}</textarea>
                            @error('address')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-8 pt-5 border-t border-default-200">
                        <a href="{{ route('locations.index') }}" class="btn border-default-200 text-default-600 px-6">Cancel</a>
                        <button type="submit" class="btn bg-primary text-white px-10 font-bold uppercase tracking-wider text-xs">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
