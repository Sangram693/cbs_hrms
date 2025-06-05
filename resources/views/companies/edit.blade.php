@extends('layouts.app')
@section('title', 'Edit Company')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Company</h2>
    <form action="{{ route('companies.update', $company) }}" method="POST">
        @csrf
        @method('PUT')        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Name</span>
                <span class="text-red-500 ml-1">*</span>
                <span class="text-sm text-gray-500">(Required)</span>
            </label>
            <input type="text" 
                name="name" 
                id="companyName"
                class="w-full border rounded px-3 py-2 {{ $errors->has('name') ? 'border-red-500' : '' }}" 
                value="{{ old('name', $company->name) }}"
                oninput="validateCompanyName(this)"
                required>
            @error('name')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
            <div id="nameError" class="text-red-600 text-sm mt-1" style="display: none;"></div>
        </div>
        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Address</span>
                <span class="text-sm text-gray-500">(Optional)</span>
            </label>
            <textarea 
                name="address" 
                class="w-full border rounded px-3 py-2 {{ $errors->has('address') ? 'border-red-500' : '' }}" 
                rows="2">{{ old('address', $company->address) }}</textarea>
            @error('address')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Phone</span>
                <span class="text-sm text-gray-500">(Optional)</span>
            </label>
            <input type="tel" 
                name="phone" 
                id="companyPhone"
                class="w-full border rounded px-3 py-2 {{ $errors->has('phone') ? 'border-red-500' : '' }}" 
                value="{{ old('phone', $company->phone) }}"
                oninput="validatePhone(this)"
                placeholder="e.g., +1234567890">
            @error('phone')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
            <div id="phoneError" class="text-red-600 text-sm mt-1" style="display: none;"></div>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Update</button>
        <a href="{{ route('companies.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">Cancel</a>
    </form>

    <script>
        function validateCompanyName(input) {
            const errorDiv = document.getElementById('nameError');
            const value = input.value.trim();
            
            if (value.length === 0) {
                input.classList.add('border-red-500');
                errorDiv.textContent = 'Company name is required';
                errorDiv.style.display = 'block';
                return false;
            } else if (value.length < 2) {
                input.classList.add('border-red-500');
                errorDiv.textContent = 'Company name must be at least 2 characters long';
                errorDiv.style.display = 'block';
                return false;
            } else {
                input.classList.remove('border-red-500');
                errorDiv.style.display = 'none';
                return true;
            }
        }

        function validatePhone(input) {
            const errorDiv = document.getElementById('phoneError');
            const value = input.value.trim();
            
            if (value.length > 0) {
                // Basic phone number validation
                const phoneRegex = /^\+?[\d\s-]{10,}$/;
                if (!phoneRegex.test(value)) {
                    input.classList.add('border-red-500');
                    errorDiv.textContent = 'Please enter a valid phone number';
                    errorDiv.style.display = 'block';
                    return false;
                }
            }
            
            input.classList.remove('border-red-500');
            errorDiv.style.display = 'none';
            return true;
        }

        // Initial validation of existing values
        document.addEventListener('DOMContentLoaded', function() {
            validateCompanyName(document.getElementById('companyName'));
            validatePhone(document.getElementById('companyPhone'));
        });

        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const isNameValid = validateCompanyName(document.getElementById('companyName'));
            const isPhoneValid = validatePhone(document.getElementById('companyPhone'));
            
            if (!isNameValid || !isPhoneValid) {
                e.preventDefault();
            }
        });
    </script>
</div>
@endsection
