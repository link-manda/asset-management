@extends('layouts.app')

@section('title', 'Edit Division')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Master Data', 'title' => 'Edit Division'])

    <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
        <div class="lg:col-span-9 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title text-base">Edit Division: {{ $division->name }}</h6>
                    
                    <form action="{{ route('divisions.update', $division) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-5">
                            <label for="name" class="inline-block mb-2 text-sm text-default-800 font-medium">Division Name</label>
                            <input type="text" name="name" id="name" class="form-input @error('name') border-danger @enderror" value="{{ old('name', $division->name) }}" required>
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end gap-3 border-t border-default-200 pt-5">
                            <a href="{{ route('divisions.index') }}" class="btn border-default-200 text-default-600 px-6">Cancel</a>
                            <button type="submit" class="btn bg-primary text-white px-10 font-bold uppercase tracking-wider text-xs">Update Division</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title text-xs font-bold uppercase tracking-widest text-default-600">Quick Stats</h6>
                    <p class="text-default-500 text-xs font-medium">This division manages <b>{{ $division->departments_count }}</b> associated departments.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
