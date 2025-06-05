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
                <label class="block mb-1">
                    <span class="font-semibold">Employee</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="employee_id" 
                        class="w-full border rounded px-3 py-2 @error('employee_id') border-red-500 @enderror">
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
            @else
                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee_id }}">
            @endif
        </div>

        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Type</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <select name="leave_type" 
                    class="w-full border rounded px-3 py-2 @error('leave_type') border-red-500 @enderror">
                <option value="">Select Type</option>
                @foreach($leaveTypes as $leaveType)
                    <option value="{{ $leaveType->name }}" {{ old('leave_type', $leave->leave_type ?? $leave->type ?? null) == $leaveType->name ? 'selected' : '' }}>
                        {{ $leaveType->name }}
                    </option>
                @endforeach
            </select>
            @error('leave_type')
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
                <span class="font-semibold">From</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="date" 
                   name="start_date" 
                   class="w-full border rounded px-3 py-2 @error('start_date') border-red-500 @enderror" 
                   value="{{ old('start_date') }}">
            @error('start_date')
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
                <span class="font-semibold">To</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="date" 
                   name="end_date" 
                   class="w-full border rounded px-3 py-2 @error('end_date') border-red-500 @enderror" 
                   value="{{ old('end_date') }}">
            @error('end_date')
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
                <span class="font-semibold">Reason</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <textarea name="reason" 
                      class="w-full border rounded px-3 py-2 @error('reason') border-red-500 @enderror">{{ old('reason') }}</textarea>
            @error('reason')
                <div class="text-red-600 text-sm mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                Create
            </button>
            <a href="{{ route('leaves.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
