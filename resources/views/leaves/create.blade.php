@extends('layouts.app')
@section('title', 'Add Leave')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Add Leave</h2>
    <form action="{{ route('leaves.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            @php
                $user = auth()->user();
                $isHr = $user->isHr();
            @endphp
           
            @if($user->isSuperAdmin() || $user->isAdmin() || $isHr)
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
            <label class="block mb-1 font-semibold">Type</label>
            <select name="leave_type" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Type</option>
                @foreach($leaveTypes as $leaveType)
                    <option value="{{ $leaveType->name }}" {{ old('leave_type', $leave->leave_type ?? $leave->type ?? null) == $leaveType->name ? 'selected' : '' }}>{{ $leaveType->name }}</option>
                @endforeach
            </select>
            @error('leave_type')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">From</label>
            <input type="date" name="start_date" class="w-full border rounded px-3 py-2" value="{{ old('start_date') }}" required>
            @error('start_date')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">To</label>
            <input type="date" name="end_date" class="w-full border rounded px-3 py-2" value="{{ old('end_date') }}" required>
            @error('end_date')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Reason</label>
            <textarea name="reason" class="w-full border rounded px-3 py-2" required>{{ old('reason') }}</textarea>
            @error('reason')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
        <a href="{{ route('leaves.index') }}" class="ml-2 text-gray-600">Cancel</a>
    </form>
</div>
@endsection
