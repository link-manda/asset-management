@extends('layouts.app')

@section('title', 'Add New Division')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Master Data', 'title' => 'Create Division'])

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('divisions.store') }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="name">Division Name</label>
                        <input class="form-input" id="name" name="name" placeholder="e.g.: Information Technology" type="text" value="{{ old('name') }}" required />
                    </div>

                    <div class="flex justify-end gap-3 pt-5 border-t border-default-200">
                        <a href="{{ route('divisions.index') }}" class="btn border-default-200 text-default-600 px-6">Cancel</a>
                        <button type="submit" class="btn bg-primary text-white px-10 font-bold uppercase tracking-widest">Save Division</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
