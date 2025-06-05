@extends('layouts.app')
@section('title', 'Edit Department')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Department</h2>
    <form action="{{ route('departments.update', $department) }}" method="POST" id="departmentForm">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Name</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="text" 
                   name="name" 
                   class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror" 
                   value="{{ old('name', $department->name) }}" 
                   required>
            @error('name')
                <div class="text-red-600 text-sm mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
            <div id="nameError" class="text-red-600 text-sm mt-1 hidden"></div>
        </div>
        <div class="mb-4">
            @if($isSuperAdmin)
                <label class="block mb-1">
                    <span class="font-semibold">Company</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="company_id" 
                        class="w-full border rounded px-3 py-2 @error('company_id') border-red-500 @enderror" 
                        required>
                    <option value="">Select Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id', $department->company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
                @error('company_id')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
                <div id="companyError" class="text-red-600 text-sm mt-1 hidden"></div>
            @else
                <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
            @endif
        </div>
        @if($employees->count())
        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">HR (Employee)</span>
                <span class="text-gray-500 text-sm ml-1">(Optional)</span>
            </label>
            <select name="hr_id" 
                    class="w-full border rounded px-3 py-2 @error('hr_id') border-red-500 @enderror">
                <option value="">Select HR</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('hr_id', $department->hr_id ?? '') == $employee->id ? 'selected' : '' }}>{{ $employee->name }} ({{ $employee->email }})</option>
                @endforeach
            </select>
            @error('hr_id')
                <div class="text-red-600 text-sm mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
            <div id="hrError" class="text-red-600 text-sm mt-1 hidden"></div>
        </div>
        @endif
        <div class="flex items-center gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                Update Department
            </button>
            <a href="{{ route('departments.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('departmentForm');
    const nameInput = form.querySelector('input[name="name"]');
    const companySelect = form.querySelector('select[name="company_id"]');
    
    function validateName() {
        const nameError = document.getElementById('nameError');
        if (!nameInput.value.trim()) {
            nameInput.classList.add('border-red-500');
            nameError.textContent = 'Department name is required';
            nameError.classList.remove('hidden');
            return false;
        }
        if (nameInput.value.length < 2) {
            nameInput.classList.add('border-red-500');
            nameError.textContent = 'Department name must be at least 2 characters';
            nameError.classList.remove('hidden');
            return false;
        }
        nameInput.classList.remove('border-red-500');
        nameError.classList.add('hidden');
        return true;
    }

    function validateCompany() {
        if (!companySelect) return true;
        const companyError = document.getElementById('companyError');
        if (!companySelect.value) {
            companySelect.classList.add('border-red-500');
            companyError.textContent = 'Please select a company';
            companyError.classList.remove('hidden');
            return false;
        }
        companySelect.classList.remove('border-red-500');
        companyError.classList.add('hidden');
        return true;
    }

    nameInput.addEventListener('input', validateName);
    if (companySelect) {
        companySelect.addEventListener('change', validateCompany);
    }

    form.addEventListener('submit', function(e) {
        const isNameValid = validateName();
        const isCompanyValid = validateCompany();

        if (!isNameValid || !isCompanyValid) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
