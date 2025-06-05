@extends('layouts.app')
@section('title', 'Edit Leave')
@section('content')
    <div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
        <h2 class="text-xl font-bold mb-4">Edit Leave</h2>
        <form action="{{ route('leaves.update', $leave) }}" method="POST" id="leaveForm">
            @csrf
            @method('PUT')
            <div class="mb-4">
                @php
                    $user = auth()->user();
                    $isHr = $user->isHr();
                @endphp

                @if ($user->isSuperAdmin() || $user->isAdmin() || $isHr)
                    <label class="block mb-1">
                        <span class="font-semibold">Employee</span>
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <select name="employee_id" 
                            id="employee_id"
                            class="w-full border rounded px-3 py-2 @error('employee_id') border-red-500 @enderror" 
                            required>
                        <option value="">Select Employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}"
                                {{ old('employee_id', $leave->employee_id) == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}</option>
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
                    <span class="font-semibold">Type</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="leave_type" 
                        id="leave_type"
                        class="w-full border rounded px-3 py-2 @error('leave_type') border-red-500 @enderror" 
                        required>
                    <option value="">Select Type</option>
                    @foreach ($leaveTypes as $leaveType)
                        <option value="{{ $leaveType->name }}"
                            {{ old('leave_type', $leave->leave_type ?? ($leave->type ?? null)) == $leaveType->name ? 'selected' : '' }}>
                            {{ $leaveType->name }}</option>
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
                <div id="leaveTypeError" class="text-red-600 text-sm mt-1 hidden"></div>
            </div>

            <div class="mb-4">
                <label class="block mb-1">
                    <span class="font-semibold">From</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <input type="date" 
                       name="start_date" 
                       id="start_date"
                       class="w-full border rounded px-3 py-2 @error('start_date') border-red-500 @enderror"
                       value="{{ old('start_date', $leave->start_date) }}" 
                       required>
                @error('start_date')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
                <div id="startDateError" class="text-red-600 text-sm mt-1 hidden"></div>
            </div>

            <div class="mb-4">
                <label class="block mb-1">
                    <span class="font-semibold">To</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <input type="date" 
                       name="end_date" 
                       id="end_date"
                       class="w-full border rounded px-3 py-2 @error('end_date') border-red-500 @enderror"
                       value="{{ old('end_date', $leave->end_date) }}" 
                       required>
                @error('end_date')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
                <div id="endDateError" class="text-red-600 text-sm mt-1 hidden"></div>
            </div>

            <div class="mb-4">
                <label class="block mb-1">
                    <span class="font-semibold">Status</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="status" 
                        id="status"
                        class="w-full border rounded px-3 py-2 @error('status') border-red-500 @enderror" 
                        required>
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
                <div id="statusError" class="text-red-600 text-sm mt-1 hidden"></div>
            </div>

            <div class="mb-4">
                <label class="block mb-1">
                    <span class="font-semibold">Reason</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <textarea name="reason" 
                          id="reason"
                          class="w-full border rounded px-3 py-2 @error('reason') border-red-500 @enderror" 
                          required>{{ old('reason', $leave->reason) }}</textarea>
                @error('reason')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
                <div id="reasonError" class="text-red-600 text-sm mt-1 hidden"></div>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('leaveForm');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const reasonInput = document.getElementById('reason');
        const leaveTypeSelect = document.getElementById('leave_type');
        const statusSelect = document.getElementById('status');
        const employeeSelect = document.getElementById('employee_id');

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

        function validateLeaveType() {
            const leaveTypeError = document.getElementById('leaveTypeError');
            if (!leaveTypeSelect.value) {
                leaveTypeSelect.classList.add('border-red-500');
                leaveTypeError.textContent = 'Please select a leave type';
                leaveTypeError.classList.remove('hidden');
                return false;
            }
            leaveTypeSelect.classList.remove('border-red-500');
            leaveTypeError.classList.add('hidden');
            return true;
        }

        function validateDates() {
            const startDateError = document.getElementById('startDateError');
            const endDateError = document.getElementById('endDateError');
            const start = new Date(startDateInput.value);
            const end = new Date(endDateInput.value);

            let isValid = true;

            if (!startDateInput.value) {
                startDateInput.classList.add('border-red-500');
                startDateError.textContent = 'Start date is required';
                startDateError.classList.remove('hidden');
                isValid = false;
            } else {
                startDateInput.classList.remove('border-red-500');
                startDateError.classList.add('hidden');
            }

            if (!endDateInput.value) {
                endDateInput.classList.add('border-red-500');
                endDateError.textContent = 'End date is required';
                endDateError.classList.remove('hidden');
                isValid = false;
            } else if (end < start) {
                endDateInput.classList.add('border-red-500');
                endDateError.textContent = 'End date must be after start date';
                endDateError.classList.remove('hidden');
                isValid = false;
            } else {
                endDateInput.classList.remove('border-red-500');
                endDateError.classList.add('hidden');
            }

            return isValid;
        }

        function validateStatus() {
            const statusError = document.getElementById('statusError');
            if (!statusSelect.value) {
                statusSelect.classList.add('border-red-500');
                statusError.textContent = 'Please select a status';
                statusError.classList.remove('hidden');
                return false;
            }
            statusSelect.classList.remove('border-red-500');
            statusError.classList.add('hidden');
            return true;
        }

        function validateReason() {
            const reasonError = document.getElementById('reasonError');
            if (!reasonInput.value.trim()) {
                reasonInput.classList.add('border-red-500');
                reasonError.textContent = 'Please provide a reason for leave';
                reasonError.classList.remove('hidden');
                return false;
            }
            if (reasonInput.value.trim().length < 10) {
                reasonInput.classList.add('border-red-500');
                reasonError.textContent = 'Reason must be at least 10 characters';
                reasonError.classList.remove('hidden');
                return false;
            }
            reasonInput.classList.remove('border-red-500');
            reasonError.classList.add('hidden');
            return true;
        }

        if (employeeSelect) {
            employeeSelect.addEventListener('change', validateEmployee);
        }
        leaveTypeSelect.addEventListener('change', validateLeaveType);
        startDateInput.addEventListener('change', validateDates);
        endDateInput.addEventListener('change', validateDates);
        statusSelect.addEventListener('change', validateStatus);
        reasonInput.addEventListener('input', validateReason);

        form.addEventListener('submit', function(e) {
            const isEmployeeValid = validateEmployee();
            const isLeaveTypeValid = validateLeaveType();
            const areDatesValid = validateDates();
            const isStatusValid = validateStatus();
            const isReasonValid = validateReason();

            if (!isEmployeeValid || !isLeaveTypeValid || !areDatesValid || 
                !isStatusValid || !isReasonValid) {
                e.preventDefault();
            }
        });
    });
    </script>
@endsection
