@extends('layouts.app')
@section('title', 'Add Department')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Add Department</h2>    <form action="{{ route('departments.store') }}" method="POST" id="departmentForm" onsubmit="return validateForm(event)">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Department Name</span>
                <span class="text-red-500 ml-1">*</span>
                <span class="text-sm text-gray-500">(Required)</span>
            </label>
            <input type="text" 
                name="name" 
                id="departmentName"
                class="w-full border rounded px-3 py-2 {{ $errors->has('name') ? 'border-red-500' : '' }}" 
                value="{{ old('name') }}"
                oninput="validateDepartmentName(this)"
                placeholder="Enter department name">
            @error('name')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
            <div id="nameError" class="text-red-600 text-sm mt-1" style="display: none;"></div>
        </div>

        <div class="mb-4">
            @if($isSuperAdmin)
                <label class="block mb-1">
                    <span class="font-semibold">Company</span>
                    <span class="text-red-500 ml-1">*</span>
                    <span class="text-sm text-gray-500">(Required)</span>
                </label>
                <select name="company_id" 
                    id="companySelect"
                    class="w-full border rounded px-3 py-2 {{ $errors->has('company_id') ? 'border-red-500' : '' }}"
                    onchange="validateCompany(this)">
                    <option value="">Select Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
                @error('company_id')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
                <div id="companyError" class="text-red-600 text-sm mt-1" style="display: none;"></div>
            @else
                <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
            @endif
        </div>

        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">HR (Employee)</span>
                <span class="text-sm text-gray-500">(Optional)</span>
            </label>
            <select name="hr_id" 
                id="hrSelect"
                class="w-full border rounded px-3 py-2 {{ $errors->has('hr_id') ? 'border-red-500' : '' }}">
                <option value="">Select HR</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('hr_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }} ({{ $employee->email }})
                    </option>
                @endforeach
            </select>
            @error('hr_id')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
            Create Department
        </button>
        <a href="{{ route('departments.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">Cancel</a>
    </form>

    <script>
        function validateDepartmentName(input) {
            const errorDiv = document.getElementById('nameError');
            const value = input.value.trim();
            
            if (value.length === 0) {
                input.classList.add('border-red-500');
                errorDiv.textContent = 'Department name is required';
                errorDiv.style.display = 'block';
                return false;
            } else if (value.length < 2) {
                input.classList.add('border-red-500');
                errorDiv.textContent = 'Department name must be at least 2 characters long';
                errorDiv.style.display = 'block';
                return false;
            } else {
                input.classList.remove('border-red-500');
                errorDiv.style.display = 'none';
                return true;
            }
        }

        function validateCompany(select) {
            const errorDiv = document.getElementById('companyError');
            
            if (!select.value) {
                select.classList.add('border-red-500');
                errorDiv.textContent = 'Please select a company';
                errorDiv.style.display = 'block';
                return false;
            } else {
                select.classList.remove('border-red-500');
                errorDiv.style.display = 'none';
                return true;
            }
        }

        function validateForm(event) {
            const isNameValid = validateDepartmentName(document.getElementById('departmentName'));
            let isValid = isNameValid;

            // Only validate company if super admin
            const companySelect = document.getElementById('companySelect');
            if (companySelect) {
                const isCompanyValid = validateCompany(companySelect);
                isValid = isValid && isCompanyValid;
            }

            if (!isValid) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        // Initialize validation on page load
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('departmentName');
            if (nameInput.value) {
                validateDepartmentName(nameInput);
            }

            const companySelect = document.getElementById('companySelect');
            if (companySelect && companySelect.value) {
                validateCompany(companySelect);
            }
        });
    </script>
</div>
@endsection
