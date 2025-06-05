@extends('layouts.app')
@section('title', 'Add Designation')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Add Designation</h2>
    <form action="{{ route('designations.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Title</label>
            <input type="text" name="title" id="title" class="w-full border rounded px-3 py-2" value="{{ old('title') }}" required>
            @error('title')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Department</label>
            <select name="department_id" id="department_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
            @error('department_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
        <a href="{{ route('designations.index') }}" class="ml-2 text-gray-600">Cancel</a>
    </form>
</div>
@endsection
