@extends('layouts.app')
@section('title', 'Add Salary')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Add Salary</h2>
    <form action="{{ route('salaries.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            @php
                $user = auth()->user();
                $isHr = $user->isHr();
            @endphp
            @if($user->isSuperAdmin() || $user->isAdmin() || $isHr)
                <label class="block mb-1">
                    <span class="font-semibold">Employee</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="employee_id" 
                        class="w-full border rounded px-3 py-2 @error('employee_id') border-red-500 @enderror">
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" 
                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}
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
            <label class="block mb-1">
                <span class="font-semibold">Month</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="month" 
                   name="salary_month" 
                   class="w-full border rounded px-3 py-2 @error('salary_month') border-red-500 @enderror" 
                   value="{{ old('salary_month') }}">
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
            <label class="block mb-1">
                <span class="font-semibold">Paid On</span>
                <span class="text-gray-500 text-sm ml-1">(Optional)</span>
            </label>
            <input type="date" 
                   name="paid_on" 
                   class="w-full border rounded px-3 py-2 @error('paid_on') border-red-500 @enderror" 
                   value="{{ old('paid_on') }}">
            @error('paid_on')
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
                <span class="font-semibold">Base Salary</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="number" 
                   name="base_salary" 
                   class="w-full border rounded px-3 py-2 @error('base_salary') border-red-500 @enderror salary-input" 
                   value="{{ old('base_salary') }}"
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
            <label class="block mb-1">
                <span class="font-semibold">Bonus</span>
                <span class="text-gray-500 text-sm ml-1">(Optional)</span>
            </label>
            <input type="number" 
                   name="bonus" 
                   class="w-full border rounded px-3 py-2 @error('bonus') border-red-500 @enderror salary-input" 
                   value="{{ old('bonus') }}"
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
            <label class="block mb-1">
                <span class="font-semibold">Deductions</span>
                <span class="text-gray-500 text-sm ml-1">(Optional)</span>
            </label>
            <input type="number" 
                   name="deductions" 
                   class="w-full border rounded px-3 py-2 @error('deductions') border-red-500 @enderror salary-input" 
                   value="{{ old('deductions') }}"
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

        <input type="hidden" name="net_salary" value="0">

        <div class="flex items-center gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                Create
            </button>
            <a href="{{ route('salaries.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple net salary calculation without validation
    const employeeSelect = document.querySelector('select[name="employee_id"]');
    const salaryInputs = document.querySelectorAll('.salary-input');
    const netSalaryInput = document.querySelector('input[name="net_salary"]');

    function calculateNetSalary() {
        const baseSalary = parseFloat(document.querySelector('input[name="base_salary"]').value) || 0;
        const bonus = parseFloat(document.querySelector('input[name="bonus"]').value) || 0;
        const deductions = parseFloat(document.querySelector('input[name="deductions"]').value) || 0;
        
        const netSalary = baseSalary + bonus - deductions;
        netSalaryInput.value = netSalary.toFixed(2);
    }

    // Update base salary when employee is selected
    if (employeeSelect) {
        employeeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const baseSalary = selectedOption.dataset.salary || 0;
            document.querySelector('input[name="base_salary"]').value = baseSalary;
            calculateNetSalary();
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
@endsection
