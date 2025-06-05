@extends('layouts.app')
@section('title', 'Add Employee')
@section('content')
    <div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
        <h2 class="text-xl font-bold mb-4">Add Employee</h2>
        <form action="{{ route('employees.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-1">
                    <span class="font-semibold">Name</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <input type="text" name="name"
                    class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror"
                    value="{{ old('name') }}">
                @error('name')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1">
                    <span class="font-semibold">Email</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <input type="email" name="email"
                    class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror"
                    value="{{ old('email') }}">
                @error('email')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>            @if (auth()->user()->isSuperAdmin())
                <div class="mb-4">
                    <label class="block mb-1">
                        <span class="font-semibold">Company</span>
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <select name="company_id" id="company_id"
                        class="w-full border rounded px-3 py-2 @error('company_id') border-red-500 @enderror">
                        <option value="">Select Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <div class="text-red-600 text-sm mt-1 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            @elseif(auth()->user()->isAdmin() || auth()->user()->isHr())
                <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
            @endif

           <div class="mb-4">
                <label class="block mb-1">
                    <span class="font-semibold">Department</span>
                </label>
                <select name="department_id" id="department_id" 
                    class="w-full border rounded px-3 py-2 @error('department_id') border-red-500 @enderror">
                    <option value="">Select Department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" data-company="{{ $department->company_id }}"
                            {{ old('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}</option>
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

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Designation</label>
                <select name="designation_id" id="designation_id" 
                    class="w-full border rounded px-3 py-2 @error('designation_id') border-red-500 @enderror">
                    <option value="">Select Designation</option>
                    @foreach ($designations as $designation)
                        <option value="{{ $designation->id }}" data-department="{{ $designation->department_id }}"
                            {{ old('designation_id') == $designation->id ? 'selected' : '' }}>
                            {{ $designation->title }}</option>
                    @endforeach
                </select>
                @error('designation_id')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1">
                    <span class="font-semibold">Role</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="user_role"
                    class="w-full border rounded px-3 py-2 @error('user_role') border-red-500 @enderror">
                    <option value="employee" {{ old('user_role', 'employee') == 'employee' ? 'selected' : '' }}>Employee
                    </option>
                    @if (auth()->user()->isSuperAdmin())
                        <option value="admin" {{ old('user_role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="super_admin" {{ old('user_role') == 'super_admin' ? 'selected' : '' }}>Super Admin
                        </option>
                    @elseif(auth()->user()->isAdmin())
                        <option value="admin" {{ old('user_role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    @endif
                </select>
                @error('user_role')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1">
                    <span class="font-semibold">Employee ID</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <input type="text" name="emp_id"
                    class="w-full border rounded px-3 py-2 @error('emp_id') border-red-500 @enderror"
                    value="{{ old('emp_id') }}">
                @error('emp_id')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1">
                    <span class="font-semibold">Phone</span>
                    <span class="text-gray-500 text-sm ml-1">(Optional)</span>
                </label>
                <input type="tel" name="phone"
                    class="w-full border rounded px-3 py-2 @error('phone') border-red-500 @enderror"
                    value="{{ old('phone') }}">
                @error('phone')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1">
                    <span class="font-semibold">Salary</span>
                    <span class="text-gray-500 text-sm ml-1">(Optional)</span>
                </label>
                <input type="number" name="salary"
                    class="w-full border rounded px-3 py-2 @error('salary') border-red-500 @enderror"
                    value="{{ old('salary') }}">
                @error('salary')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1">
                    <span class="font-semibold">Fingerprint ID</span>
                    <span class="text-gray-500 text-sm ml-1">(Optional)</span>
                </label>
                <input type="text" name="fingerprint_id"
                    class="w-full border rounded px-3 py-2 @error('fingerprint_id') border-red-500 @enderror"
                    value="{{ old('fingerprint_id') }}">
                @error('fingerprint_id')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="flex items-center gap-2">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                    Save
                </button>
                <a href="{{ route('employees.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
<script>        document.addEventListener('DOMContentLoaded', function() {
            // Company and Department filtering
            const companySelect = document.getElementById('company_id');
            const departmentSelect = document.getElementById('department_id');
            const designationSelect = document.getElementById('designation_id');
            const companyId = companySelect ? companySelect.value : '{{ auth()->user()->company_id }}';

            function filterDepartments() {
                Array.from(departmentSelect.options).forEach(option => {
                    if (!option.value) return;
                    option.style.display = option.getAttribute('data-company') === companyId ? '' : 'none';
                });
                if (departmentSelect.selectedOptions.length && departmentSelect.selectedOptions[0].style.display === 'none') {
                    departmentSelect.value = '';
                }
                filterDesignations();
            }            function filterDesignations() {
                const departmentId = departmentSelect.value;
                Array.from(designationSelect.options).forEach(option => {
                    if (!option.value) return;
                    // Hide all options if no department selected, otherwise show only matching ones
                    option.style.display = (departmentId && option.getAttribute('data-department') === departmentId) ? '' : 'none';
                });
            }// Only add company change listener if the select exists (for super admin)
            if (companySelect) {
                companySelect.addEventListener('change', filterDepartments);
            }
            departmentSelect.addEventListener('change', filterDesignations);
            filterDepartments(); // Initial filter
        });
    </script>
@endsection
