@extends('layouts.app')
@section('title', 'Add Designation')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Add Designation</h2>
    <form action="{{ route('designations.store') }}" method="POST" id="designationForm">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Title</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   class="w-full border rounded px-3 py-2 @error('title') border-red-500 @enderror" 
                   value="{{ old('title') }}" 
                   required>
            @error('title')
                <div class="text-red-600 text-sm mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
            <div id="titleError" class="text-red-600 text-sm mt-1 hidden"></div>
        </div>

        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Department</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <select name="department_id" 
                    id="department_id" 
                    class="w-full border rounded px-3 py-2 @error('department_id') border-red-500 @enderror" 
                    required>
                <option value="">Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
            @error('department_id')
                <div class="text-red-600 text-sm mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
            <div id="departmentError" class="text-red-600 text-sm mt-1 hidden"></div>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                Create
            </button>
            <a href="{{ route('designations.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('designationForm');
    const titleInput = document.getElementById('title');
    const departmentSelect = document.getElementById('department_id');
    
    function validateTitle() {
        const titleError = document.getElementById('titleError');
        if (!titleInput.value.trim()) {
            titleInput.classList.add('border-red-500');
            titleError.textContent = 'Title is required';
            titleError.classList.remove('hidden');
            return false;
        }
        if (titleInput.value.length < 2) {
            titleInput.classList.add('border-red-500');
            titleError.textContent = 'Title must be at least 2 characters';
            titleError.classList.remove('hidden');
            return false;
        }
        titleInput.classList.remove('border-red-500');
        titleError.classList.add('hidden');
        return true;
    }

    function validateDepartment() {
        const departmentError = document.getElementById('departmentError');
        if (!departmentSelect.value) {
            departmentSelect.classList.add('border-red-500');
            departmentError.textContent = 'Please select a department';
            departmentError.classList.remove('hidden');
            return false;
        }
        departmentSelect.classList.remove('border-red-500');
        departmentError.classList.add('hidden');
        return true;
    }

    titleInput.addEventListener('input', validateTitle);
    departmentSelect.addEventListener('change', validateDepartment);

    form.addEventListener('submit', function(e) {
        const isTitleValid = validateTitle();
        const isDepartmentValid = validateDepartment();

        if (!isTitleValid || !isDepartmentValid) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
