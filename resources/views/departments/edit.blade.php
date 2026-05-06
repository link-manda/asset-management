@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Master Data', 'title' => 'Edit Department'])

    <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
        <div class="lg:col-span-9 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title text-base">Edit Department: {{ $department->name }}</h6>
                    
                    <form action="{{ route('departments.update', $department) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label for="division_id" class="inline-block mb-2 text-sm text-default-800 font-medium">Division</label>
                                <select name="division_id" id="division_id" class="form-select @error('division_id') border-danger @enderror" required>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}" {{ old('division_id', $department->division_id) == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                                    @endforeach
                                </select>
                                @error('division_id')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-1">
                                <label for="name" class="inline-block mb-2 text-sm text-default-800 font-medium">Department Name</label>
                                <input type="text" name="name" id="name" class="form-input @error('name') border-danger @enderror" value="{{ old('name', $department->name) }}" required>
                                @error('name')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 border-t border-default-200 pt-5 mt-5">
                            <a href="{{ route('departments.index') }}" class="btn border-default-200 text-default-600 px-6">Cancel</a>
                            <button type="submit" class="btn bg-primary text-white px-10 font-bold uppercase tracking-wider text-xs">Update Department</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title text-xs font-bold uppercase tracking-widest text-default-600">Quick Stats</h6>
                    <div class="flex flex-col gap-3">
                        <div class="flex justify-between items-center">
                            <span class="text-default-500 text-xs">Total Users:</span>
                            <span class="text-default-800 font-bold">{{ $department->users_count }} Members</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-default-500 text-xs">Parent Division:</span>
                            <span class="text-primary font-bold text-xs">{{ $department->division->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
