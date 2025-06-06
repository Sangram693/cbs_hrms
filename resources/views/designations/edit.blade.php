@extends('layouts.app')
@section('title', 'Edit Designation')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Designation</h2>
    <form action="{{ route('designations.update', $designation) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Title</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="text" 
                name="title" 
                class="w-full border rounded px-3 py-2 @error('title') border-red-500 @enderror" 
                value="{{ old('title', $designation->title) }}">
            @error('title')
                <div class="text-red-600 text-sm mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
        </div>        @if($isSuperAdmin)
        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Company</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <select name="company_id" id="company_id"
                class="w-full border rounded px-3 py-2 @error('company_id') border-red-500 @enderror">
                <option value="">Select Company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id', $designation->company_id) == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
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
        </div>
        @else
            <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
        @endif

        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Department</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <select name="department_id" 
                class="w-full border rounded px-3 py-2 @error('department_id') border-red-500 @enderror"
                {{ $isSuperAdmin ? 'disabled' : '' }}>
                <option value="">Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" 
                        data-company="{{ $department->company_id }}"
                        {{ old('department_id', $designation->department_id) == $department->id ? 'selected' : '' }}>
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
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                Update
            </button>
            <a href="{{ route('designations.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                Cancel
            </a>
        </div>    </form>
</div>

@if($isSuperAdmin)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.querySelector('select[name="company_id"]');
    const departmentSelect = document.querySelector('select[name="department_id"]');
    
    function filterDepartmentOptions() {
        const selectedCompanyId = companySelect.value;
        departmentSelect.disabled = !selectedCompanyId;
        
        Array.from(departmentSelect.options).forEach(option => {
            if (option.value === '') return; // Skip the placeholder option
            const companyId = option.getAttribute('data-company');
            option.style.display = !selectedCompanyId || companyId === selectedCompanyId ? '' : 'none';
        });

        // Reset department selection if company changes and current selection is not from selected company
        const currentDept = departmentSelect.options[departmentSelect.selectedIndex];
        if (currentDept && currentDept.value && currentDept.getAttribute('data-company') !== selectedCompanyId) {
            departmentSelect.value = '';
        }
    }

    companySelect.addEventListener('change', filterDepartmentOptions);
    filterDepartmentOptions(); // Run initially to set correct state
});
</script>
@endif
@endsection
