@extends('layouts.app')
@section('title', 'Add Department')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Add Department</h2>
    <form action="{{ route('departments.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Department Name</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="text" 
                name="name" 
                class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror" 
                value="{{ old('name') }}"
                placeholder="Enter department name">
            @error('name')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>        <div class="mb-4">
            @if(auth()->user()->isSuperAdmin())
                <label class="block mb-1">
                    <span class="font-semibold">Company</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="company_id" id="company_id"
                    class="w-full border rounded px-3 py-2 @error('company_id') border-red-500 @enderror">
                    <option value="">Select Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
                @error('company_id')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            @else
                <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
            @endif        </div>

        <button type="submit"class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
            Create Department
        </button>
        <a href="{{ route('departments.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">Cancel</a>
    </form>
</div>

@if($isSuperAdmin)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.querySelector('select[name="company_id"]');
    const hrSelect = document.querySelector('select[name="hr_id"]');
    
    function filterHrOptions() {
        const selectedCompanyId = companySelect.value;
        hrSelect.disabled = !selectedCompanyId;
        
        Array.from(hrSelect.options).forEach(option => {
            if (option.value === '') return; // Skip the placeholder option
            const companyId = option.getAttribute('data-company');
            option.style.display = !selectedCompanyId || companyId === selectedCompanyId ? '' : 'none';
        });

        // Reset HR selection if company changes
        hrSelect.value = '';
    }

    companySelect.addEventListener('change', filterHrOptions);
    filterHrOptions(); // Run initially to set correct state
});
</script>
@endif
@endsection
