@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Add Employee</h2>
    <form action="{{ route('employees.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name') }}" required>
            @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" value="{{ old('email') }}" required>
            @error('email')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        @if(auth()->user()->isSuperAdmin())
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Company <span class="text-red-500">*</span></label>
            <select name="company_id" id="company_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                @endforeach
            </select>
            @error('company_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        @elseif(auth()->user()->isAdmin())
            <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
        @endif
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Department <span class="text-red-500">*</span></label>
            <select name="department_id" id="department_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" data-company="{{ $department->company_id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                @endforeach
            </select>
            @error('department_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">            <label class="block mb-1 font-semibold">Designation <span class="text-red-500">*</span></label>
            <select name="designation_id" id="designation_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Designation</option>
                @foreach($designations as $designation)
                    <option value="{{ $designation->id }}" data-department="{{ $designation->department_id }}" {{ old('designation_id') == $designation->id ? 'selected' : '' }}>{{ $designation->title }}</option>
                @endforeach
            </select>
            @error('designation_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">            <label class="block mb-1 font-semibold">Role <span class="text-red-500">*</span></label>
            <select name="user_role" class="w-full border rounded px-3 py-2" required>
                <option value="employee" {{ old('user_role', 'employee') == 'employee' ? 'selected' : '' }}>Employee</option>
                @if(auth()->user()->isSuperAdmin())
                    <option value="admin" {{ old('user_role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super_admin" {{ old('user_role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                @elseif(auth()->user()->isAdmin())
                    <option value="admin" {{ old('user_role') == 'admin' ? 'selected' : '' }}>Admin</option>
                @endif
            </select>
            @error('user_role')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Employee ID <span class="text-red-500">*</span></label>
            <input type="text" name="emp_id" class="w-full border rounded px-3 py-2" value="{{ old('emp_id') }}" required>
            @error('emp_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Phone</label>
            <input type="text" name="phone" class="w-full border rounded px-3 py-2" value="{{ old('phone') }}">
            @error('phone')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Salary <span class="text-red-500">*</span></label>
            <input type="number" name="salary" class="w-full border rounded px-3 py-2" value="{{ old('salary') }}" step="0.01" min="0" required>
            @error('salary')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="fingerprint_id" class="block text-gray-700 font-semibold mb-2">Fingerprint ID <span class="text-red-500">*</span></label>
            <input type="text" name="fingerprint_id" id="fingerprint_id" value="{{ old('fingerprint_id', isset($employee) ? $employee->fingerprint_id : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition" required>
            @error('fingerprint_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
        <a href="{{ route('employees.index') }}" class="ml-2 text-gray-600">Cancel</a>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {    const companySelect = document.getElementById('company_id');
    const departmentSelect = document.getElementById('department_id');
    const designationSelect = document.getElementById('designation_id');    function filterDepartments() {
        if (companySelect) {
            const companyId = companySelect.value;
            Array.from(departmentSelect.options).forEach(option => {
                if (!option.value) return;
                option.style.display = option.getAttribute('data-company') === companyId ? '' : 'none';
            });
            // Reset department if not matching
            if (departmentSelect.selectedOptions.length && departmentSelect.selectedOptions[0].style.display === 'none') {
                departmentSelect.value = '';
            }
        }        filterDesignations(); // Also filter designations
    }

    function filterDesignations() {
        const departmentId = departmentSelect.value;
        Array.from(designationSelect.options).forEach(option => {
            if (!option.value) return;
            option.style.display = option.getAttribute('data-department') === departmentId ? '' : 'none';
        });
        if (designationSelect.selectedOptions.length && designationSelect.selectedOptions[0].style.display === 'none') {
            designationSelect.value = '';
        }
    }

    if (companySelect) companySelect.addEventListener('change', filterDepartments);
    departmentSelect.addEventListener('change', filterDesignations);
    filterDepartments(); // Initial filter
});
</script>
@endsection
