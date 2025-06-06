@extends('layouts.app')
@section('title', 'Edit Training')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Training</h2>
    <form action="{{ route('trainings.update', $training) }}" method="POST">
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
                    <select name="company_id" id="company_id"
                            class="w-full border rounded px-3 py-2 @error('company_id') border-red-500 @enderror">
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" 
                                {{ old('company_id', request()->input('company_id', $training->company_id)) == $company->id ? 'selected' : '' }}>
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

            @if($user->isSuperAdmin() || $user->isAdmin() || $isHr)
                <label class="block mb-1">
                    <span class="font-semibold">Employee</span>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <select name="employee_id" id="employee_id"
                        class="w-full border rounded px-3 py-2 @error('employee_id') border-red-500 @enderror"
                        {{ $user->isSuperAdmin() && !request('company_id', $training->company_id) ? 'disabled' : '' }}>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" 
                                {{ old('employee_id', $training->employee_id) == $employee->id ? 'selected' : '' }}>
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
            <label class="block mb-1 font-semibold">
                Training Name
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="text" 
                   name="training_name" 
                   class="w-full border rounded px-3 py-2 @error('training_name') border-red-500 @enderror"
                   value="{{ old('training_name', $training->training_name) }}">
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
            <select name="status" 
                    class="w-full border rounded px-3 py-2 @error('status') border-red-500 @enderror">
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

        <div class="mb-4">
            <label class="block mb-1 font-semibold">
                Start Date
                <span class="text-red-500 ml-1" id="start_date_required" style="display: none">*</span>
            </label>
            <input type="date" 
                   name="start_date" 
                   class="w-full border rounded px-3 py-2 @error('start_date') border-red-500 @enderror"
                   value="{{ old('start_date', $training->start_date ? date('Y-m-d', strtotime($training->start_date)) : '') }}">
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
                <span class="text-red-500 ml-1" id="end_date_required" style="display: none">*</span>
            </label>
            <input type="date" 
                   name="end_date" 
                   class="w-full border rounded px-3 py-2 @error('end_date') border-red-500 @enderror"
                   value="{{ old('end_date', $training->end_date ? date('Y-m-d', strtotime($training->end_date)) : '') }}">
            @error('end_date')
            <div class="text-red-600 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Update
            </button>
            <a href="{{ route('trainings.index') }}" class="text-gray-600 hover:text-gray-800">
                Cancel
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.getElementById('company_id');
    const employeeSelect = document.getElementById('employee_id');
    const statusSelect = document.querySelector('select[name="status"]');
    const startDateRequired = document.getElementById('start_date_required');
    const endDateRequired = document.getElementById('end_date_required');

    // Handle company selection
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            if (companyId) {
                // Show loading state
                employeeSelect.disabled = true;
                employeeSelect.innerHTML = '<option value="">Loading employees...</option>';
                
                // Fetch employees for selected company
                window.location.href = `{{ route('trainings.edit', $training) }}?company_id=${companyId}`;
            } else {
                employeeSelect.disabled = true;
                employeeSelect.innerHTML = '<option value="">Select Company First</option>';
            }
        });
    }

    // Handle status changes
    if (statusSelect) {
        function updateDateValidation() {
            const status = statusSelect.value;
            if (status === 'Ongoing' || status === 'Completed') {
                startDateRequired.style.display = 'inline';
                if (status === 'Completed') {
                    endDateRequired.style.display = 'inline';
                } else {
                    endDateRequired.style.display = 'none';
                }
            } else {
                startDateRequired.style.display = 'none';
                endDateRequired.style.display = 'none';
            }
        }

        statusSelect.addEventListener('change', updateDateValidation);
        updateDateValidation(); // Run initially to set correct state
    }
});
</script>
@endpush
@endsection
