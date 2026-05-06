@extends('layouts.app')

@section('title', 'Add New Department')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Master Data', 'title' => 'Create Department'])

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('departments.store') }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="division_id">Parent Division</label>
                        <select class="form-input" id="division_id" name="division_id" required>
                            <option value="">-- Select Division --</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="name">Department Name</label>
                        <input class="form-input" id="name" name="name" placeholder="e.g.: Infrastructure & Support" type="text" value="{{ old('name') }}" required />
                    </div>

                    <div class="flex justify-end gap-3 pt-5 border-t border-default-200">
                        <a href="{{ route('departments.index') }}" class="btn border-default-200 text-default-600 px-6">Cancel</a>
                        <button type="submit" class="btn bg-primary text-white px-10 font-bold uppercase tracking-widest">Save Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
