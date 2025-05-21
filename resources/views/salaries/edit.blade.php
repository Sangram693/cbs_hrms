@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Salary</h2>
    <form action="{{ route('salaries.update', $salary->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Employee</label>
            <select name="employee_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Employee</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('employee_id', $salary->employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                @endforeach
            </select>
            @error('employee_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Month</label>
            <input type="month" name="salary_month" class="w-full border rounded px-3 py-2" value="{{ old('salary_month', \Illuminate\Support\Str::substr($salary->salary_month, 0, 7)) }}" required>
            @error('salary_month')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Paid On</label>
            <input type="date" name="paid_on" class="w-full border rounded px-3 py-2" value="{{ old('paid_on', $salary->paid_on) }}">
            @error('paid_on')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Base Salary</label>
            <input type="number" name="base_salary" class="w-full border rounded px-3 py-2" value="{{ old('base_salary', $salary->base_salary) }}" required>
            @error('base_salary')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Bonus</label>
            <input type="number" name="bonus" class="w-full border rounded px-3 py-2" value="{{ old('bonus', $salary->bonus) }}">
            @error('bonus')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Deductions</label>
            <input type="number" name="deductions" class="w-full border rounded px-3 py-2" value="{{ old('deductions', $salary->deductions) }}">
            @error('deductions')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('salaries.index') }}" class="ml-2 text-gray-600">Cancel</a>
    </form>
</div>
@endsection
