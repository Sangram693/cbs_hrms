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
                <div class="mb-4">
                    @if (auth()->user()->isSuperAdmin())
                        <label class="block mb-1 font-semibold">Company</label>
                        <select name="company_id"
                            class="w-full border rounded px-3 py-2 @error('company_id') border-red-500 @enderror">
                            <option value="">Select Company</option>
                            @foreach (\App\Models\Company::all() as $company)
                                <option value="{{ $company->id }}"
                                    {{ old('company_id', $attendance->company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <div class="text-red-600 text-sm">{{ $message }}</div>
                        @enderror
                    @else
                        <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
                    @endif
                </div> <label class="block mb-1 font-semibold">Employee</label>
                @if ($user->isSuperAdmin() || $user->isAdmin() || $isHr)
                    <select name="employee_id"
                        class="w-full border rounded px-3 py-2 @error('employee_id') border-red-500 @enderror"
                        {{ $user->isSuperAdmin() ? 'disabled' : '' }}>
                        <option value="">Select Employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" data-company="{{ $employee->company_id }}"
                                {{ old('employee_id', $attendance->employee_id) == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                @else
                    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee_id }}">
                @endif
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Date</label>
                <input type="date" name="date"
                    class="w-full border rounded px-3 py-2 @error('date') border-red-500 @enderror"
                    value="{{ old('date', $attendance->date) }}">
                @error('date')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 date-field" id="checkInField">
                <label class="block mb-1">
                    <span class="font-semibold">Check In</span>
                    <span class="text-sm ml-1" id="checkInRequired">(Optional)</span>
                </label> <input type="time" name="check_in" id="checkIn"
                    class="w-full border rounded px-3 py-2 @error('check_in') border-red-500 @enderror"
                    value="{{ old('check_in', $attendance->check_in) }}" step="1">
                @error('check_in')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 date-field" id="checkOutField">
                <label class="block mb-1">
                    <span class="font-semibold">Check Out</span>
                    <span class="text-sm ml-1" id="checkOutRequired">(Optional)</span>
                </label>
                <input type="time" name="check_out" id="checkOut"
                    class="w-full border rounded px-3 py-2 @error('check_out') border-red-500 @enderror"
                    value="{{ old('check_out', $attendance->check_out) }}" step="1">
                @error('check_out')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Status</label>
                <select name="status" id="attendanceStatus"
                    class="w-full border rounded px-3 py-2 @error('status') border-red-500 @enderror"
                    onchange="handleStatusChange()">
                    <option value="">Select Status</option>
                    <option value="Present" {{ old('status', $attendance->status) == 'Present' ? 'selected' : '' }}>Present
                    </option>
                    <option value="Absent" {{ old('status', $attendance->status) == 'Absent' ? 'selected' : '' }}>Absent
                    </option>
                    <option value="Leave" {{ old('status', $attendance->status) == 'Leave' ? 'selected' : '' }}>Leave
                    </option>
                </select>
                @error('status')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>



            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('attendance.index') }}" class="ml-2 text-gray-600">Cancel</a>
        </form>

        <script>
            function handleStatusChange() {
                const status = document.getElementById('attendanceStatus').value;
                const checkInField = document.getElementById('checkInField');
                const checkOutField = document.getElementById('checkOutField');
                const checkInRequired = document.getElementById('checkInRequired');
                const checkOutRequired = document.getElementById('checkOutRequired');

                // Reset styling
                checkInRequired.textContent = '(Optional)';
                checkOutRequired.textContent = '(Optional)';
                checkInRequired.className = 'text-sm text-gray-500 ml-1';
                checkOutRequired.className = 'text-sm text-gray-500 ml-1';
                checkInField.style.opacity = '1';
                checkOutField.style.opacity = '1';

                if (status === 'Present') {
                    // Indicate that both times are needed for Present status
                    checkInRequired.textContent = '(Required)';
                    checkOutRequired.textContent = '(Required)';
                    checkInRequired.className = 'text-sm text-red-500 ml-1';
                    checkOutRequired.className = 'text-sm text-red-500 ml-1';
                } else if (status === 'Absent' || status === 'Leave') {
                    // Times optional and dimmed
                    checkInField.style.opacity = '0.5';
                    checkOutField.style.opacity = '0.5';
                    // Don't clear values - preserve them in case the user changes status back
                }
            } // Handle employee filtering based on company selection
            function handleCompanyChange() {
                const companySelect = document.querySelector('select[name="company_id"]');
                const employeeSelect = document.querySelector('select[name="employee_id"]');

                if (!companySelect || !employeeSelect) return; // Exit if not superadmin view

                const selectedCompanyId = companySelect.value;
                employeeSelect.disabled = !selectedCompanyId;

                Array.from(employeeSelect.options).forEach(option => {
                    if (option.value === '') return; // Skip the placeholder option
                    const companyId = option.getAttribute('data-company');
                    option.style.display = !selectedCompanyId || companyId === selectedCompanyId ? '' : 'none';
                });

                // Reset employee selection if it's not from the selected company
                const currentEmployee = employeeSelect.options[employeeSelect.selectedIndex];
                if (currentEmployee && currentEmployee.value && currentEmployee.getAttribute('data-company') !==
                    selectedCompanyId) {
                    employeeSelect.value = '';
                }
            } // Handle time input events
            function handleTimeInput(input) {
                input.addEventListener('change', function() {
                    // Format the time to include seconds if they're not present
                    let time = this.value;
                    if (time && time.length === 5) {
                        time += ':00';
                        this.value = time;
                    }
                    // Blur the input to close the time picker
                    this.blur();
                });

                // Close time picker on enter key
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        this.blur();
                    }
                });
            }

            // Initialize the form state
            document.addEventListener('DOMContentLoaded', function() {
                handleStatusChange();

                const companySelect = document.querySelector('select[name="company_id"]');
                if (companySelect) {
                    companySelect.addEventListener('change', handleCompanyChange);
                    handleCompanyChange(); // Run initially to set correct state
                }

                // Setup time input handlers
                const checkInInput = document.getElementById('checkIn');
                const checkOutInput = document.getElementById('checkOut');
                handleTimeInput(checkInInput);
                handleTimeInput(checkOutInput);
            });
        </script>
    </div>
@endsection
