@extends('layouts.app')
@section('title', 'Edit Leave Type')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Leave Type</h2>
    <form action="{{ route('leavetypes.update', $leavetype) }}" method="POST" id="leaveTypeForm">
        @csrf
        @method('PUT')
        @php $user = auth()->user(); @endphp
        @if($user->isSuperAdmin())
            <div class="mb-4">
                <label class="block mb-1">
                    <span class="font-semibold">Company</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="company_id" 
                        id="company_id"
                        class="w-full border rounded px-3 py-2 @error('company_id') border-red-500 @enderror" 
                        required>
                    <option value="">Select Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id', $leavetype->company_id) == $company->id ? 'selected' : '' }}>
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
                <div id="companyError" class="text-red-600 text-sm mt-1 hidden"></div>
            </div>
        @endif

        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Leave Type Name</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="text" 
                   name="name" 
                   id="name"
                   class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror" 
                   value="{{ old('name', $leavetype->name) }}" 
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

        <div class="flex items-center gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                Update
            </button>
            <a href="{{ route('leavetypes.index', $user->isSuperAdmin() ? ['company_id'=>$leavetype->company_id] : []) }}" 
               class="text-gray-600 hover:text-gray-800 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('leaveTypeForm');
    const nameInput = document.getElementById('name');
    const companySelect = document.getElementById('company_id');

    function validateName() {
        const nameError = document.getElementById('nameError');
        if (!nameInput.value.trim()) {
            nameInput.classList.add('border-red-500');
            nameError.textContent = 'Leave type name is required';
            nameError.classList.remove('hidden');
            return false;
        }
        if (nameInput.value.trim().length < 2) {
            nameInput.classList.add('border-red-500');
            nameError.textContent = 'Leave type name must be at least 2 characters';
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
