@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Attendance</h2>
    <form action="{{ route('attendance.update', $attendance) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            @php
                $user = auth()->user();
                $isHr = $user->isHr();
            @endphp
            <label class="block mb-1 font-semibold">Employee</label>
            @if($user->isSuperAdmin() || $user->isAdmin() || $isHr)
                <select name="employee_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id', $attendance->employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                    @endforeach
                </select>
                @error('employee_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            @else
                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee_id }}">
            @endif
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Date</label>
            <input type="date" name="date" class="w-full border rounded px-3 py-2" value="{{ old('date', $attendance->date) }}" required>
            @error('date')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Check In</label>
            <input type="time" name="check_in" class="w-full border rounded px-3 py-2" value="{{ old('check_in', $attendance->check_in) }}">
            @error('check_in')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Check Out</label>
            <input type="time" name="check_out" class="w-full border rounded px-3 py-2" value="{{ old('check_out', $attendance->check_out) }}">
            @error('check_out')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Status</option>
                <option value="Present" {{ old('status', $attendance->status) == 'Present' ? 'selected' : '' }}>Present</option>
                <option value="Absent" {{ old('status', $attendance->status) == 'Absent' ? 'selected' : '' }}>Absent</option>
                <option value="Leave" {{ old('status', $attendance->status) == 'Leave' ? 'selected' : '' }}>Leave</option>
            </select>
            @error('status')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            @if(auth()->user()->isSuperAdmin())
                <label class="block mb-1 font-semibold">Company</label>
                <select name="company_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Company</option>
                    @foreach(\App\Models\Company::all() as $company)
                        <option value="{{ $company->id }}" {{ old('company_id', $attendance->company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
                @error('company_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            @else
                <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
            @endif
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('attendance.index') }}" class="ml-2 text-gray-600">Cancel</a>
    </form>
</div>
@endsection
