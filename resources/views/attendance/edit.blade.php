@extends('layouts.app')
@section('title', 'Edit Attendance')
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
        </div>        <div class="mb-4 date-field" id="checkInField">
            <label class="block mb-1">
                <span class="font-semibold">Check In</span>
                <span class="text-sm text-gray-500 ml-1" id="checkInRequired"></span>
            </label>
            <input type="time" name="check_in" id="checkIn" class="w-full border rounded px-3 py-2" value="{{ old('check_in', $attendance->check_in) }}">
            @error('check_in')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4 date-field" id="checkOutField">
            <label class="block mb-1">
                <span class="font-semibold">Check Out</span>
                <span class="text-sm text-gray-500 ml-1" id="checkOutRequired"></span>
            </label>
            <input type="time" name="check_out" id="checkOut" class="w-full border rounded px-3 py-2" value="{{ old('check_out', $attendance->check_out) }}">
            @error('check_out')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">            <label class="block mb-1 font-semibold">Status</label>
            <select name="status" id="attendanceStatus" class="w-full border rounded px-3 py-2" required onchange="handleStatusChange()">
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
        </div>        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('attendance.index') }}" class="ml-2 text-gray-600">Cancel</a>
    </form>

    <script>
        function handleStatusChange() {
            const status = document.getElementById('attendanceStatus').value;
            const checkInField = document.getElementById('checkInField');
            const checkOutField = document.getElementById('checkOutField');
            const checkIn = document.getElementById('checkIn');
            const checkOut = document.getElementById('checkOut');
            const checkInRequired = document.getElementById('checkInRequired');
            const checkOutRequired = document.getElementById('checkOutRequired');

            // Reset all
            checkInRequired.textContent = '(Optional)';
            checkOutRequired.textContent = '(Optional)';
            checkInRequired.className = 'text-sm text-gray-500 ml-1';
            checkOutRequired.className = 'text-sm text-gray-500 ml-1';
            checkIn.removeAttribute('required');
            checkOut.removeAttribute('required');
            checkInField.style.opacity = '1';
            checkOutField.style.opacity = '1';

            if (status === 'Present') {
                // Both times required
                checkIn.setAttribute('required', 'required');
                checkOut.setAttribute('required', 'required');
                checkInRequired.textContent = '(Required)';
                checkOutRequired.textContent = '(Required)';
                checkInRequired.className = 'text-sm text-red-500 ml-1';
                checkOutRequired.className = 'text-sm text-red-500 ml-1';
            } else if (status === 'Absent' || status === 'Leave') {
                // Times optional and dimmed
                checkInField.style.opacity = '0.5';
                checkOutField.style.opacity = '0.5';
            }

            // Validate time relationship if both are filled
            if (checkIn.value && checkOut.value) {
                validateTimes();
            }
        }

        function validateTimes() {
            const checkIn = document.getElementById('checkIn').value;
            const checkOut = document.getElementById('checkOut').value;
            const checkOutError = document.querySelector('[data-error="check_out"]');
            
            if (checkIn && checkOut) {
                if (checkOut <= checkIn) {
                    if (!checkOutError) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'text-red-600 text-sm mt-1';
                        errorDiv.setAttribute('data-error', 'check_out');
                        errorDiv.textContent = 'Check-out time must be after check-in time';
                        document.getElementById('checkOut').parentNode.appendChild(errorDiv);
                    }
                    return false;
                } else if (checkOutError) {
                    checkOutError.remove();
                }
            }
            return true;
        }

        // Add event listeners for time inputs
        document.getElementById('checkIn').addEventListener('change', validateTimes);
        document.getElementById('checkOut').addEventListener('change', validateTimes);

        // Initialize the form state
        document.addEventListener('DOMContentLoaded', function() {
            handleStatusChange();
        });
    </script>
</div>
@endsection
