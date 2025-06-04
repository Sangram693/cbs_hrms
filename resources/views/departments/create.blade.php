@extends('layouts.app')
@section('title', 'Add Department')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Add Department</h2>
    <form action="{{ route('departments.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name') }}" required>
            @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            @if($isSuperAdmin)
                <label class="block mb-1 font-semibold">Company</label>
                <select name="company_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
                @error('company_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            @else
                <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
            @endif
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">HR (Employee)</label>
            <select name="hr_id" class="w-full border rounded px-3 py-2">
                <option value="">Select HR</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('hr_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }} ({{ $employee->email }})</option>
                @endforeach
            </select>
            @error('hr_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
        <a href="{{ route('departments.index') }}" class="ml-2 text-gray-600">Cancel</a>
    </form>
</div>
@endsection
