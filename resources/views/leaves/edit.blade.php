@extends('layouts.app')
@section('title', 'Edit Leave')
@section('content')
    <div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
        <h2 class="text-xl font-bold mb-4">Edit Leave</h2>
        <form action="{{ route('leaves.update', $leave) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
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
                        <select name="company_id" id="company_select" 
                                class="w-full border rounded px-3 py-2 @error('company_id') border-red-500 @enderror">
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $companyId) == $company->id ? 'selected' : '' }}>
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
                @endif

                @if ($user->isSuperAdmin() || $user->isAdmin() || $isHr)
                    <label class="block mb-1">
                        <span class="font-semibold">Employee</span>
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <select name="employee_id" id="employee_select"
                            class="w-full border rounded px-3 py-2 @error('employee_id') border-red-500 @enderror"
                            {{ $user->isSuperAdmin() && !request('company_id') ? 'disabled' : '' }}>
                        <option value="">Select Employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id', $leave->employee_id) == $employee->id ? 'selected' : '' }}>
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
                <select name="leave_type" id="leave_type_select"
                        class="w-full border rounded px-3 py-2 @error('leave_type') border-red-500 @enderror"
                        {{ $user->isSuperAdmin() && !request('company_id') ? 'disabled' : '' }}>
                    <option value="">Select Type</option>
                    @foreach($leaveTypes as $leaveType)
                        <option value="{{ $leaveType->name }}" {{ old('leave_type', $leave->leave_type) == $leaveType->name ? 'selected' : '' }}>
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
                       value="{{ old('start_date', $leave->start_date) }}">
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
                       value="{{ old('end_date', $leave->end_date) }}">
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
                    <span class="font-semibold">Status</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="status" 
                        class="w-full border rounded px-3 py-2 @error('status') border-red-500 @enderror">
                    <option value="">Select Status</option>
                    <option value="Pending" {{ old('status', $leave->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ old('status', $leave->status) == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ old('status', $leave->status) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('status')
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
                          class="w-full border rounded px-3 py-2 @error('reason') border-red-500 @enderror">{{ old('reason', $leave->reason) }}</textarea>
                @error('reason')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <input type="hidden" name="company_id" value="{{ $leave->company_id }}">
            
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                    Update
                </button>
                <a href="{{ route('leaves.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    @if($user->isSuperAdmin())
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const companySelect = document.getElementById('company_select');
        const employeeSelect = document.getElementById('employee_select');
        const leaveTypeSelect = document.getElementById('leave_type_select');
        
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            if (companyId) {
                window.location.href = '{{ route('leaves.edit', $leave) }}?company_id=' + companyId;
            } else {
                employeeSelect.disabled = true;
                leaveTypeSelect.disabled = true;
                employeeSelect.innerHTML = '<option value="">Select Employee</option>';
                leaveTypeSelect.innerHTML = '<option value="">Select Type</option>';
            }
        });
    });
    </script>
    @endif

@endsection
