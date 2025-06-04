@extends('layouts.app')
@section('title', 'Edit Designation')
@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Edit Designation</h2>
    <form action="{{ route('designations.update', $designation) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="title" class="block text-gray-700 font-bold mb-2">Title:</label>
            <input type="text" name="title" id="title" class="form-input w-full @error('title') border-red-500 @enderror" value="{{ old('title', $designation->title) }}" required>
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="department_id" class="block text-gray-700 font-bold mb-2">Department:</label>
            <select name="department_id" id="department_id" class="form-select w-full @error('department_id') border-red-500 @enderror" required>
                <option value="">Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id', $designation->department_id) == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
            @error('department_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <a href="{{ route('designations.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        </div>
    </form>
</div>
@endsection
