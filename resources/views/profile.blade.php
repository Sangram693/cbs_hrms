@extends('layouts.app')
@section('title', 'Profile')
@section('content')
    <div class="max-w-lg mx-auto mt-8 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4 text-center">My Profile</h2>
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            @php
                $user = auth()->user();
                $employee = $user->isSuperAdmin() ? null : $user->employee;
                $isSuperadmin = $user->isSuperAdmin();
            @endphp

            <h3 class="font-semibold text-lg mb-3">Account Information</h3>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2"
                    value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2"
                    value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>
            @if (!$isSuperadmin)
                <h3 class="font-semibold text-lg mt-6 mb-3">Employee Information</h3>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Employee ID</label>
                    <input type="text" name="emp_id" class="w-full border rounded px-3 py-2 bg-gray-50"
                        value="{{ old('emp_id', $employee->emp_id ?? '') }}" readonly>
                    @error('emp_id')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Phone</label>
                    <input type="text" name="phone" class="w-full border rounded px-3 py-2"
                        value="{{ old('phone', $employee->phone ?? '') }}">
                    @error('phone')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Department</label>
                    <input type="text" class="w-full border rounded px-3 py-2 bg-gray-50"
                        value="{{ $employee?->department?->name ?? 'Not Assigned' }}" readonly>
                    @error('department_id')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Designation</label>
                    <input type="text" class="w-full border rounded px-3 py-2 bg-gray-50"
                        value="{{ $employee?->designation ? $employee->designation->title : 'Not Assigned' }}" readonly>
                    @error('designation_id')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Hire Date</label>
                    <input type="date" name="hire_date" class="w-full border rounded px-3 py-2 bg-gray-50"
                        value="{{ old('hire_date', $employee->hire_date ?? '') }}" readonly>
                    @error('hire_date')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                @if ($employee)
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Status</label>
                        <input type="text" class="w-full border rounded px-3 py-2 bg-gray-50"
                            value="{{ $employee->status ?? 'N/A' }}" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Company</label>
                        <input type="text" class="w-full border rounded px-3 py-2 bg-gray-50"
                            value="{{ $employee->company->name ?? 'Not Assigned' }}" readonly>
                    </div>
                @endif
            @endif

            <h3 class="font-semibold text-lg mt-6 mb-3">Security</h3>
            <div class="mb-6">
                <label class="block mb-1 font-semibold">Password <span class="text-gray-500 text-xs">(leave blank to keep
                        current)</span></label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2">
                @error('password')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="flex justify-between items-center">
                <button type="submit"
                    class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">Update
                    Profile</button>
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
@endsection
