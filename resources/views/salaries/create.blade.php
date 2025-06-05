@extends('layouts.app')
@section('title', 'Add Salary')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Add Salary</h2>
    <form action="{{ route('salaries.store') }}" method="POST" id="salaryForm">
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
                        id="employee_id"
                        class="w-full border rounded px-3 py-2 @error('employee_id') border-red-500 @enderror" 
                        required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
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
                <div id="employeeError" class="text-red-600 text-sm mt-1 hidden"></div>
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
                   id="salary_month"
                   class="w-full border rounded px-3 py-2 @error('salary_month') border-red-500 @enderror" 
                   value="{{ old('salary_month') }}" 
                   required>
            @error('salary_month')
                <div class="text-red-600 text-sm mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
            <div id="monthError" class="text-red-600 text-sm mt-1 hidden"></div>
        </div>

        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Paid On</span>
                <span class="text-gray-500 text-sm ml-1">(Optional)</span>
            </label>
            <input type="date" 
                   name="paid_on" 
                   id="paid_on"
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
            <div id="paidOnError" class="text-red-600 text-sm mt-1 hidden"></div>
        </div>

        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Base Salary</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="number" 
                   name="base_salary" 
                   id="base_salary"
                   class="w-full border rounded px-3 py-2 @error('base_salary') border-red-500 @enderror" 
                   value="{{ old('base_salary') }}" 
                   min="0"
                   step="0.01"
                   required>
            @error('base_salary')
                <div class="text-red-600 text-sm mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
            <div id="baseSalaryError" class="text-red-600 text-sm mt-1 hidden"></div>
        </div>

        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Bonus</span>
                <span class="text-gray-500 text-sm ml-1">(Optional)</span>
            </label>
            <input type="number" 
                   name="bonus" 
                   id="bonus"
                   class="w-full border rounded px-3 py-2 @error('bonus') border-red-500 @enderror" 
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
            <div id="bonusError" class="text-red-600 text-sm mt-1 hidden"></div>
        </div>

        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Deductions</span>
                <span class="text-gray-500 text-sm ml-1">(Optional)</span>
            </label>
            <input type="number" 
                   name="deductions" 
                   id="deductions"
                   class="w-full border rounded px-3 py-2 @error('deductions') border-red-500 @enderror" 
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
            <div id="deductionsError" class="text-red-600 text-sm mt-1 hidden"></div>
        </div>

        <input type="hidden" name="net_salary" id="net_salary" value="">

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
    const form = document.getElementById('salaryForm');
    const base = document.getElementById('base_salary');
    const bonus = document.getElementById('bonus');
    const deductions = document.getElementById('deductions');
    const net = document.getElementById('net_salary');
    const month = document.getElementById('salary_month');
    const paidOn = document.getElementById('paid_on');
    const employeeSelect = document.getElementById('employee_id');

    // Prepare employee salary map
    const employeeSalaries = {
        @foreach($employees as $employee)
            '{{ $employee->id }}': {{ $employee->salary ?? 0 }},
        @endforeach
    };

    function validateEmployee() {
        if (!employeeSelect) return true;
        const employeeError = document.getElementById('employeeError');
        if (!employeeSelect.value) {
            employeeSelect.classList.add('border-red-500');
            employeeError.textContent = 'Please select an employee';
            employeeError.classList.remove('hidden');
            return false;
        }
        employeeSelect.classList.remove('border-red-500');
        employeeError.classList.add('hidden');
        return true;
    }

    function validateMonth() {
        const monthError = document.getElementById('monthError');
        if (!month.value) {
            month.classList.add('border-red-500');
            monthError.textContent = 'Please select a salary month';
            monthError.classList.remove('hidden');
            return false;
        }
        month.classList.remove('border-red-500');
        monthError.classList.add('hidden');
        return true;
    }

    function validatePaidOn() {
        if (!paidOn.value) return true;
        const paidOnError = document.getElementById('paidOnError');
        const paidDate = new Date(paidOn.value);
        const today = new Date();
        
        if (paidDate > today) {
            paidOn.classList.add('border-red-500');
            paidOnError.textContent = 'Paid date cannot be in the future';
            paidOnError.classList.remove('hidden');
            return false;
        }
        paidOn.classList.remove('border-red-500');
        paidOnError.classList.add('hidden');
        return true;
    }

    function validateBaseSalary() {
        const baseSalaryError = document.getElementById('baseSalaryError');
        if (!base.value || parseFloat(base.value) < 0) {
            base.classList.add('border-red-500');
            baseSalaryError.textContent = 'Please enter a valid base salary';
            baseSalaryError.classList.remove('hidden');
            return false;
        }
        base.classList.remove('border-red-500');
        baseSalaryError.classList.add('hidden');
        return true;
    }

    function validateBonus() {
        if (!bonus.value) return true;
        const bonusError = document.getElementById('bonusError');
        if (parseFloat(bonus.value) < 0) {
            bonus.classList.add('border-red-500');
            bonusError.textContent = 'Bonus cannot be negative';
            bonusError.classList.remove('hidden');
            return false;
        }
        bonus.classList.remove('border-red-500');
        bonusError.classList.add('hidden');
        return true;
    }

    function validateDeductions() {
        if (!deductions.value) return true;
        const deductionsError = document.getElementById('deductionsError');
        if (parseFloat(deductions.value) < 0) {
            deductions.classList.add('border-red-500');
            deductionsError.textContent = 'Deductions cannot be negative';
            deductionsError.classList.remove('hidden');
            return false;
        }
        deductions.classList.remove('border-red-500');
        deductionsError.classList.add('hidden');
        return true;
    }

    function calcNet() {
        const b = parseFloat(base.value) || 0;
        const bo = parseFloat(bonus.value) || 0;
        const d = parseFloat(deductions.value) || 0;
        net.value = (b + bo - d).toFixed(2);
    }

    // Event listeners for real-time validation
    if (employeeSelect) {
        employeeSelect.addEventListener('change', function() {
            validateEmployee();
            const selectedId = this.value;
            if (employeeSalaries[selectedId] !== undefined) {
                base.value = employeeSalaries[selectedId];
                calcNet();
            }
        });
    }

    month.addEventListener('change', validateMonth);
    paidOn.addEventListener('change', validatePaidOn);
    base.addEventListener('input', function() {
        validateBaseSalary();
        calcNet();
    });
    bonus.addEventListener('input', function() {
        validateBonus();
        calcNet();
    });
    deductions.addEventListener('input', function() {
        validateDeductions();
        calcNet();
    });

    // Calculate net salary on form submit
    form.addEventListener('submit', function(e) {
        const isEmployeeValid = validateEmployee();
        const isMonthValid = validateMonth();
        const isPaidOnValid = validatePaidOn();
        const isBaseSalaryValid = validateBaseSalary();
        const isBonusValid = validateBonus();
        const isDeductionsValid = validateDeductions();

        if (!isEmployeeValid || !isMonthValid || !isPaidOnValid || 
            !isBaseSalaryValid || !isBonusValid || !isDeductionsValid) {
            e.preventDefault();
            return;
        }

        calcNet();
    });

    // Initial calculation
    calcNet();
});
</script>
@endsection
