@extends('layouts.app')

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
            @if($user->isSuperAdmin() || $user->isAdmin())
                <label class="block mb-1 font-semibold">Employee</label>
                <select name="employee_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                    @endforeach
                </select>
                @error('employee_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            @elseif($isHr)
                <label class="block mb-1 font-semibold">Employee</label>
                <select name="employee_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                    @endforeach
                </select>
                @error('employee_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            @else
                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee_id }}">
            @endif
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Month</label>
            <input type="month" name="salary_month" class="w-full border rounded px-3 py-2" value="{{ old('salary_month') }}" required>
            @error('salary_month')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Paid On</label>
            <input type="date" name="paid_on" class="w-full border rounded px-3 py-2" value="{{ old('paid_on') }}">
            @error('paid_on')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Base Salary</label>
            <input type="number" name="base_salary" class="w-full border rounded px-3 py-2" value="{{ old('base_salary') }}" required>
            @error('base_salary')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Bonus</label>
            <input type="number" name="bonus" class="w-full border rounded px-3 py-2" value="{{ old('bonus') }}">
            @error('bonus')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Deductions</label>
            <input type="number" name="deductions" class="w-full border rounded px-3 py-2" value="{{ old('deductions') }}">
            @error('deductions')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <input type="hidden" name="net_salary" id="net_salary" value="">
        <script>
        // Auto-calculate net salary before submit and auto-fill base salary on employee select
        document.addEventListener('DOMContentLoaded', function() {
            const base = document.querySelector('input[name=base_salary]');
            const bonus = document.querySelector('input[name=bonus]');
            const deductions = document.querySelector('input[name=deductions]');
            const net = document.getElementById('net_salary');
            const form = document.querySelector('form');
            const employeeSelect = document.querySelector('select[name=employee_id]');
            // Prepare employee salary map
            const employeeSalaries = {
                @foreach($employees as $employee)
                    '{{ $employee->id }}': {{ $employee->salary ?? 0 }},
                @endforeach
            };
            function calcNet() {
                const b = parseFloat(base.value) || 0;
                const bo = parseFloat(bonus.value) || 0;
                const d = parseFloat(deductions.value) || 0;
                net.value = (b + bo - d).toFixed(2);
            }
            [base, bonus, deductions].forEach(i => i.addEventListener('input', calcNet));
            form.addEventListener('submit', calcNet);
            calcNet();
            // Auto-fill base salary when employee changes
            if (employeeSelect) {
                employeeSelect.addEventListener('change', function() {
                    const selectedId = this.value;
                    if (employeeSalaries[selectedId] !== undefined) {
                        base.value = employeeSalaries[selectedId];
                        calcNet();
                    }
                });
            }
        });
        </script>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
        <a href="{{ route('salaries.index') }}" class="ml-2 text-gray-600">Cancel</a>
    </form>
</div>
@endsection
