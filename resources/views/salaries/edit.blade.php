@extends('layouts.app')
@section('title', 'Edit Salary')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Salary</h2>
    
    @php
        $user = auth()->user();
        $isHr = $user->isHr();
    @endphp

    <form action="{{ route('salaries.update', $salary) }}" method="POST">
        @csrf
        @method('PUT')

        @if($user->isSuperAdmin())
            <div class="mb-4">
                <label class="block mb-1">
                    <span class="font-semibold">Company</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="company_id" id="company_select" 
                        class="w-full border rounded px-3 py-2 @error('company_id') border-red-500 @enderror">
                    <option value="">Select Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id', $companyId) == $company->id ? 'selected' : '' }}>
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
        @endif

        <div class="mb-4">
            @if($user->isSuperAdmin() || $user->isAdmin() || $isHr)
                <label class="block mb-1">
                    <span class="font-semibold">Employee</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="employee_id" id="employee_select"
                        class="w-full border rounded px-3 py-2 @error('employee_id') border-red-500 @enderror"
                        {{ $user->isSuperAdmin() && !request('company_id') ? 'disabled' : '' }}>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" 
                                {{ old('employee_id', $salary->employee_id) == $employee->id ? 'selected' : '' }}
                                data-salary="{{ $employee->salary ?? 0 }}">
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            @else
                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee_id }}">
            @endif
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">
                Month
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="month" 
                   name="salary_month" 
                   class="w-full border rounded px-3 py-2 @error('salary_month') border-red-500 @enderror" 
                   value="{{ old('salary_month', \Illuminate\Support\Str::substr($salary->salary_month, 0, 7)) }}">
            @error('salary_month')
            <div class="text-red-600 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">
                Base Salary
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="number" 
                   name="base_salary" 
                   class="w-full border rounded px-3 py-2 @error('base_salary') border-red-500 @enderror salary-input" 
                   value="{{ old('base_salary', $salary->base_salary) }}"
                   min="0"
                   step="0.01">
            @error('base_salary')
            <div class="text-red-600 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">
                Bonus
                <span class="text-gray-500 text-sm ml-1">(Optional)</span>
            </label>
            <input type="number" 
                   name="bonus" 
                   class="w-full border rounded px-3 py-2 @error('bonus') border-red-500 @enderror salary-input" 
                   value="{{ old('bonus', $salary->bonus) }}"
                   min="0"
                   step="0.01">
            @error('bonus')
            <div class="text-red-600 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">
                Deductions
                <span class="text-gray-500 text-sm ml-1">(Optional)</span>
            </label>
            <input type="number" 
                   name="deductions" 
                   class="w-full border rounded px-3 py-2 @error('deductions') border-red-500 @enderror salary-input" 
                   value="{{ old('deductions', $salary->deductions) }}"
                   min="0"
                   step="0.01">
            @error('deductions')
            <div class="text-red-600 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
            @enderror
        </div>

        <input type="hidden" name="net_salary" value="{{ $salary->net_salary }}">

        <div class="flex items-center gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Update
            </button>
            <a href="{{ route('salaries.index') }}" class="text-gray-600 hover:text-gray-800">
                Cancel
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const salaryInputs = document.querySelectorAll('.salary-input');
    const netSalaryInput = document.querySelector('input[name="net_salary"]');
    const baseSalaryInput = document.querySelector('input[name="base_salary"]');
    const employeeSelect = document.getElementById('employee_select');
    const companySelect = document.getElementById('company_select');

    function calculateNetSalary() {
        const baseSalary = parseFloat(baseSalaryInput.value) || 0;
        const bonus = parseFloat(document.querySelector('input[name="bonus"]').value) || 0;
        const deductions = parseFloat(document.querySelector('input[name="deductions"]').value) || 0;
        
        const netSalary = baseSalary + bonus - deductions;
        netSalaryInput.value = netSalary.toFixed(2);
    }

    // Update base salary when employee is selected
    if (employeeSelect) {
        employeeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.dataset.salary) {
                baseSalaryInput.value = selectedOption.dataset.salary;
                calculateNetSalary();
            }
        });
    }

    // Handle company selection
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            if (companyId) {
                window.location.href = '{{ route('salaries.edit', $salary) }}?company_id=' + companyId;
            } else {
                employeeSelect.disabled = true;
                employeeSelect.innerHTML = '<option value="">Select Employee</option>';
            }
        });
    }

    // Recalculate net salary when any salary-related input changes
    salaryInputs.forEach(input => {
        input.addEventListener('input', calculateNetSalary);
    });

    // Initial calculation
    calculateNetSalary();
});
</script>
@endpush
@endsection
