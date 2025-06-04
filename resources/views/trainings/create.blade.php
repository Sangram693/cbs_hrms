@extends('layouts.app')
@section('title', 'Add Training')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Add Training</h2>
    <form action="{{ route('trainings.store') }}" method="POST">
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
            <label class="block mb-1 font-semibold">Training Name</label>
            <input type="text" name="training_name" class="w-full border rounded px-3 py-2" value="{{ old('training_name') }}" required>
            @error('training_name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Status</option>
                <option value="Ongoing" {{ old('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Planned" {{ old('status') == 'Planned' ? 'selected' : '' }}>Planned</option>
            </select>
            @error('status')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Start Date</label>
            <input type="date" name="start_date" class="w-full border rounded px-3 py-2" value="{{ old('start_date') }}">
            @error('start_date')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">End Date</label>
            <input type="date" name="end_date" class="w-full border rounded px-3 py-2" value="{{ old('end_date') }}">
            @error('end_date')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
        <a href="{{ route('trainings.index') }}" class="ml-2 text-gray-600">Cancel</a>
    </form>
</div>
@endsection
