@extends('layouts.app')

@section('title', 'Add New Location')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Master Data', 'title' => 'Create Location'])

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('locations.store') }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="name">Location Name</label>
                        <input class="form-input" id="name" name="name" placeholder="e.g.: Warehouse A" type="text" value="{{ old('name') }}" required />
                    </div>

                    <div class="mb-5">
                        <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="parent_id">Parent Location</label>
                        <select class="form-input" id="parent_id" name="parent_id">
                            <option value="">- Set as Top Level Location -</option>
                            @foreach($allLocations as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-8">
                        <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="address">Full Address</label>
                        <textarea class="form-input" id="address" name="address" placeholder="Detailed location address..." rows="3" required>{{ old('address') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-5 border-t border-default-200">
                        <a href="{{ route('locations.index') }}" class="btn border-default-200 text-default-600 px-6">Cancel</a>
                        <button type="submit" class="btn bg-primary text-white px-10 font-bold uppercase tracking-widest">Save Location</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
