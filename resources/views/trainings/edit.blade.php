@extends('layouts.app')
@section('title', 'Edit Training')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Training</h2>
    <form action="{{ route('trainings.update', $training) }}" method="POST" id="trainingForm" onsubmit="return validateForm(event)">
        @csrf
        @method('PUT')
        <div class="mb-4">
            @php
                $user = auth()->user();
                $isHr = $user->isHr();
            @endphp
            @if($user->isSuperAdmin() || $user->isAdmin())
                <label class="block mb-1 font-semibold">
                    Employee
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="employee_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id', $training->employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
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
            @elseif($isHr)
                <label class="block mb-1 font-semibold">
                    Employee
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="employee_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id', $training->employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
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
            <label class="block mb-1 font-semibold">
                Training Name
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="text" name="training_name" class="w-full border rounded px-3 py-2" value="{{ old('training_name', $training->training_name) }}" required>
            @error('training_name')
            <div class="text-red-600 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">
                Status
                <span class="text-red-500 ml-1">*</span>
            </label>
            <select name="status" id="trainingStatus" class="w-full border rounded px-3 py-2" required onchange="handleStatusChange()">
                <option value="">Select Status</option>
                <option value="Not Started" {{ old('status', $training->status) == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                <option value="Ongoing" {{ old('status', $training->status) == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="Completed" {{ old('status', $training->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
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
        <div class="mb-4 date-field" id="startDateField">
            <label class="block mb-1">
                <span class="font-semibold">Start Date</span>
                <span class="text-sm text-gray-500 ml-1" id="startDateRequired">(Optional)</span>
            </label>
            <input type="date" name="start_date" id="startDate" class="w-full border rounded px-3 py-2" value="{{ old('start_date', $training->start_date) }}">
            @error('start_date')
            <div class="text-red-600 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-4 date-field" id="endDateField">
            <label class="block mb-1">
                <span class="font-semibold">End Date</span>
                <span class="text-sm text-gray-500 ml-1" id="endDateRequired">(Optional)</span>
            </label>
            <input type="date" name="end_date" id="endDate" class="w-full border rounded px-3 py-2" value="{{ old('end_date', $training->end_date) }}">
            @error('end_date')
            <div class="text-red-600 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
            @enderror
        </div>
        <script>
            function handleStatusChange() {
                const status = document.getElementById('trainingStatus').value;
                const startDateField = document.getElementById('startDateField');
                const endDateField = document.getElementById('endDateField');
                const startDate = document.getElementById('startDate');
                const endDate = document.getElementById('endDate');
                const startDateRequired = document.getElementById('startDateRequired');
                const endDateRequired = document.getElementById('endDateRequired');

                // Reset all
                startDateRequired.textContent = '(Optional)';
                endDateRequired.textContent = '(Optional)';
                startDateRequired.className = 'text-sm text-gray-500 ml-1';
                endDateRequired.className = 'text-sm text-gray-500 ml-1';
                startDate.dataset.required = 'false';
                endDate.dataset.required = 'false';

                if (status === 'Not Started') {
                    // Both dates optional
                    startDateField.style.opacity = '0.7';
                    endDateField.style.opacity = '0.7';
                } else if (status === 'Ongoing') {
                    // Start date required, end date optional
                    startDateField.style.opacity = '1';
                    endDateField.style.opacity = '0.7';
                    startDate.dataset.required = 'true';
                    startDateRequired.textContent = '(Required)';
                    startDateRequired.className = 'text-sm text-red-500 ml-1';
                } else if (status === 'Completed') {
                    // Both dates required
                    startDateField.style.opacity = '1';
                    endDateField.style.opacity = '1';
                    startDate.dataset.required = 'true';
                    endDate.dataset.required = 'true';
                    startDateRequired.textContent = '(Required)';
                    endDateRequired.textContent = '(Required)';
                    startDateRequired.className = 'text-sm text-red-500 ml-1';
                    endDateRequired.className = 'text-sm text-red-500 ml-1';
                }
            }

            function validateForm(event) {
                const status = document.getElementById('trainingStatus').value;
                const startDate = document.getElementById('startDate');
                const endDate = document.getElementById('endDate');
                let isValid = true;
                
                // Clear previous error messages
                clearErrorMessage('start_date');
                clearErrorMessage('end_date');

                if ((status === 'Ongoing' || status === 'Completed') && !startDate.value) {
                    showErrorMessage('start_date', 'The start date is required for ' + status + ' status');
                    isValid = false;
                }

                if (status === 'Completed' && !endDate.value) {
                    showErrorMessage('end_date', 'The end date is required for Completed status');
                    isValid = false;
                }

                if (startDate.value && endDate.value && new Date(endDate.value) < new Date(startDate.value)) {
                    showErrorMessage('end_date', 'End date must be after or equal to start date');
                    isValid = false;
                }

                return isValid;
            }

            function showErrorMessage(fieldName, message) {
                const field = document.querySelector(`[name="${fieldName}"]`);
                const existingError = document.getElementById(fieldName + '-error');
                if (existingError) {
                    existingError.remove();
                }

                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-red-600 text-sm mt-1 flex items-center';
                errorDiv.id = fieldName + '-error';
                errorDiv.innerHTML = `
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    ${message}
                `;
                
                field.parentNode.appendChild(errorDiv);
                field.classList.add('border-red-500');
            }

            function clearErrorMessage(fieldName) {
                const existingError = document.getElementById(fieldName + '-error');
                if (existingError) {
                    existingError.remove();
                }
                const field = document.querySelector(`[name="${fieldName}"]`);
                field.classList.remove('border-red-500');
            }

            // Call the function on page load to set initial state
            document.addEventListener('DOMContentLoaded', handleStatusChange);
        </script>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
        <a href="{{ route('trainings.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">Cancel</a>
    </form>
</div>
@endsection
