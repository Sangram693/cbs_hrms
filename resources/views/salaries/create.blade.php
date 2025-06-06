@extends('layouts.app')
@section('title', 'Add Salary')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Add Salary</h2>
    <form action="{{ route('salaries.store') }}" method="POST">
        @csrf
        @php
            $user = auth()->user();
            $isHr = $user->isHr();
        @endphp

        @if($user->isSuperAdmin())
            <div class="mb-4">
                <label class="block mb-1">
                    <span class="font-semibold">Company</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="company_id" id="company_id"
                        class="w-full border rounded px-3 py-2 @error('company_id') border-red-500 @enderror">
                    <option value="">Select Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" 
                                {{ old('company_id', request()->input('company_id')) == $company->id ? 'selected' : '' }}>
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
        @endif        <div class="mb-4">
            @if($user->isSuperAdmin() || $user->isAdmin() || $isHr)
                <label class="block mb-1">
                    <span class="font-semibold">Employee</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="employee_id" id="employee_id"
                        class="w-full border rounded px-3 py-2 @error('employee_id') border-red-500 @enderror"
                        {{ $user->isSuperAdmin() && !request()->input('company_id') ? 'disabled' : '' }}>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" 
                                {{ (old('employee_id', $selectedEmployeeId) == $employee->id) ? 'selected' : '' }}
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
                   value="{{ old('salary_month', $selectedMonth) }}">
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
                   value="{{ old('base_salary', $baseSalary) }}"
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

        <div class="mb-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <label class="block mb-2">
                    <span class="font-semibold">Approved Bills for Selected Month</span>
                </label>
                <div class="space-y-2">
                    @if($bills->isNotEmpty())
                        @foreach($bills as $bill)
                            <div class="flex justify-between items-center text-sm">
                                <span>{{ $bill->bill_type }} ({{ $bill->bill_date->format('Y-m-d') }})</span>
                                <span>{{ number_format($bill->amount, 2) }}</span>
                            </div>
                        @endforeach
                        <div class="border-t mt-2 pt-2 flex justify-between font-semibold">
                            <span>Total Bill Amount:</span>
                            <span>{{ number_format($bills->sum('amount'), 2) }}</span>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No approved bills found for the selected month.</p>
                    @endif
                </div>
            </div>
            <input type="hidden" name="bill_amount" value="{{ $bills->sum('amount') }}" id="billAmount">
        </div>

        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Net Salary</span>
            </label>
            <input type="text" 
                   class="w-full border rounded px-3 py-2 bg-gray-50" 
                   id="displayNetSalary" 
                   readonly>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.getElementById('company_id');
    const employeeSelect = document.getElementById('employee_id');
    const salaryInputs = document.querySelectorAll('.salary-input');
    const netSalaryInput = document.querySelector('input[name="net_salary"]');
    const displayNetSalaryInput = document.getElementById('displayNetSalary');
    const baseSalaryInput = document.querySelector('input[name="base_salary"]');
    const billAmountInput = document.getElementById('billAmount');
    const monthInput = document.querySelector('input[name="salary_month"]');

    function calculateNetSalary() {
        const baseSalary = parseFloat(baseSalaryInput.value) || 0;
        const bonus = parseFloat(document.querySelector('input[name="bonus"]').value) || 0;
        const deductions = parseFloat(document.querySelector('input[name="deductions"]').value) || 0;
        const billAmount = parseFloat(billAmountInput.value) || 0;
        
        const netSalary = baseSalary + bonus + billAmount - deductions;
        netSalaryInput.value = netSalary.toFixed(2);
        displayNetSalaryInput.value = netSalary.toFixed(2);
    }

    // Update base salary and redirect when employee is selected
    if (employeeSelect) {
        employeeSelect.addEventListener('change', function() {
            const selectedEmployee = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const currentBaseSalary = selectedOption && selectedOption.dataset.salary ? selectedOption.dataset.salary : 0;
            const selectedMonth = monthInput.value;
            const companyId = companySelect ? companySelect.value : null;

            // Update base salary
            baseSalaryInput.value = currentBaseSalary;
            calculateNetSalary();

            // Build URL with all parameters
            const params = new URLSearchParams();
            if (selectedEmployee) params.append('employee_id', selectedEmployee);
            if (selectedMonth) params.append('salary_month', selectedMonth);
            if (currentBaseSalary) params.append('base_salary', currentBaseSalary);
            if (companyId) params.append('company_id', companyId);

            // Redirect with parameters
            window.location.href = `{{ route('salaries.create') }}?${params.toString()}`;
        });
    }

    // Handle month change event
    if (monthInput) {
        monthInput.addEventListener('change', function() {
            const selectedMonth = this.value;
            const selectedEmployee = employeeSelect ? employeeSelect.value : null;
            const currentBaseSalary = baseSalaryInput.value;
            const companyId = companySelect ? companySelect.value : null;

            if (selectedEmployee) {
                // Build URL with all parameters
                const params = new URLSearchParams();
                params.append('employee_id', selectedEmployee);
                if (selectedMonth) params.append('salary_month', selectedMonth);
                if (currentBaseSalary) params.append('base_salary', currentBaseSalary);
                if (companyId) params.append('company_id', companyId);

                // Redirect with parameters
                window.location.href = `{{ route('salaries.create') }}?${params.toString()}`;
            }
        });
    }

    // Handle company change event
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            const currentBaseSalary = baseSalaryInput.value;
            const selectedMonth = monthInput.value;

            if (companyId) {
                const params = new URLSearchParams();
                params.append('company_id', companyId);
                if (currentBaseSalary) params.append('base_salary', currentBaseSalary);
                if (selectedMonth) params.append('salary_month', selectedMonth);
                window.location.href = `{{ route('salaries.create') }}?${params.toString()}`;
            } else {
                employeeSelect.disabled = true;
                employeeSelect.innerHTML = '<option value="">Select Company First</option>';
            }
        });
    }

    // Set initial base salary from URL if available
    const urlParams = new URLSearchParams(window.location.search);
    const urlBaseSalary = urlParams.get('base_salary');
    if (urlBaseSalary) {
        baseSalaryInput.value = urlBaseSalary;
        calculateNetSalary();
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
