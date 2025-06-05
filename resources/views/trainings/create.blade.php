@extends('layouts.app')
@section('title', 'Add Training')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const startDateInput = form.querySelector('input[name="start_date"]');
    const endDateInput = form.querySelector('input[name="end_date"]');
    const employeeSelect = form.querySelector('select[name="employee_id"]');
    const statusSelect = form.querySelector('select[name="status"]');
    const trainingNameInput = form.querySelector('input[name="training_name"]');

    // Validate employee selection
    if (employeeSelect) {
        employeeSelect.addEventListener('change', function() {
            if (!this.value) {
                showError(this, 'Please select an employee');
                this.setCustomValidity('Please select an employee');
            } else {
                hideError(this);
                this.setCustomValidity('');
            }
        });
    }

    // Validate training name
    if (trainingNameInput) {
        trainingNameInput.addEventListener('input', function() {
            if (this.value.trim().length < 3) {
                showError(this, 'Training name must be at least 3 characters long');
                this.setCustomValidity('Training name must be at least 3 characters long');
            } else {
                hideError(this);
                this.setCustomValidity('');
            }
        });
    }

    // Validate status
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            if (!this.value) {
                showError(this, 'Please select a status');
                this.setCustomValidity('Please select a status');
            } else {
                hideError(this);
                this.setCustomValidity('');
            }
        });
    }

    // Validate dates
    function validateDates() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        
        if (startDate > endDate) {
            showError(endDateInput, 'End date must be after start date');
            endDateInput.setCustomValidity('End date must be after start date');
            return false;
        } else {
            hideError(endDateInput);
            endDateInput.setCustomValidity('');
            return true;
        }
    }

    if (startDateInput && endDateInput) {
        startDateInput.addEventListener('change', validateDates);
        endDateInput.addEventListener('change', validateDates);
    }

    // Show error message
    function showError(element, message) {
        let errorDiv = element.nextElementSibling;
        if (!errorDiv || !errorDiv.classList.contains('text-red-600')) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'text-red-600 text-sm mt-1 flex items-center validation-error';
            errorDiv.innerHTML = `
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                ${message}
            `;
            element.parentNode.insertBefore(errorDiv, element.nextSibling);
        }
    }

    // Hide error message
    function hideError(element) {
        const errorDiv = element.nextElementSibling;
        if (errorDiv && errorDiv.classList.contains('validation-error')) {
            errorDiv.remove();
        }
    }

    // Form submission validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validate all required fields
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value) {
                isValid = false;
                showError(field, 'This field is required');
                field.setCustomValidity('This field is required');
            }
        });

        // Validate dates
        if (!validateDates()) {
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>
@endpush

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
                <label class="block mb-1 font-semibold">
                    Employee
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="employee_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
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
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
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
            <input type="text" name="training_name" class="w-full border rounded px-3 py-2" value="{{ old('training_name') }}" required>
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
            <select name="status" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Status</option>
                <option value="Not Started" {{ old('status') == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                <option value="Ongoing" {{ old('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
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
            <label class="block mb-1 font-semibold">
                Start Date
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="date" name="start_date" class="w-full border rounded px-3 py-2" value="{{ old('start_date') }}" required>
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
            <label class="block mb-1 font-semibold">
                End Date
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="date" name="end_date" class="w-full border rounded px-3 py-2" value="{{ old('end_date') }}" required>
            @error('end_date')
            <div class="text-red-600 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
            @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create</button>
        <a href="{{ route('trainings.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">Cancel</a>
    </form>
</div>
@endsection
