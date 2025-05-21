@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Leave</h2>
    <form action="{{ route('leaves.update', $leave) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Employee</label>
            <select name="employee_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Employee</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('employee_id', $leave->employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                @endforeach
            </select>
            @error('employee_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Type</label>
            <input type="text" name="type" class="w-full border rounded px-3 py-2" value="{{ old('type', $leave->type) }}" required>
            @error('type')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">From</label>
            <input type="date" name="from" class="w-full border rounded px-3 py-2" value="{{ old('from', $leave->from) }}" required>
            @error('from')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">To</label>
            <input type="date" name="to" class="w-full border rounded px-3 py-2" value="{{ old('to', $leave->to) }}" required>
            @error('to')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Status</option>
                <option value="Pending" {{ old('status', $leave->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ old('status', $leave->status) == 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Rejected" {{ old('status', $leave->status) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            @error('status')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('leaves.index') }}" class="ml-2 text-gray-600">Cancel</a>
    </form>
</div>
@endsection
