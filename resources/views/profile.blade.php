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
                <input type="text" name="name"
                    class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror"
                    value="{{ old('name', $user->name) }}">
                @error('name')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email"
                    class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror"
                    value="{{ old('email', $user->email) }}">
                @error('email')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            @if (!$isSuperadmin)
                <h3 class="font-semibold text-lg mt-6 mb-3">Employee Information</h3>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Employee ID</label>
                    <input type="text" name="emp_id" class="w-full border rounded px-3 py-2 bg-gray-50"
                        value="{{ old('emp_id', $employee->emp_id ?? '') }}" readonly>
                    @error('emp_id')
                        <div class="text-red-600 text-sm mt-1 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Phone</label>
                    <input type="text" name="phone"
                        class="w-full border rounded px-3 py-2 @error('phone') border-red-500 @enderror"
                        value="{{ old('phone', $employee->phone ?? '') }}">
                    @error('phone')
                        <div class="text-red-600 text-sm mt-1 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Department</label>
                    <input type="text" class="w-full border rounded px-3 py-2 bg-gray-50"
                        value="{{ $employee?->department?->name ?? 'Not Assigned' }}" readonly>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Designation</label>
                    <input type="text" class="w-full border rounded px-3 py-2 bg-gray-50"
                        value="{{ $employee?->designation ? $employee->designation->title : 'Not Assigned' }}" readonly>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Hire Date</label>
                    <input type="date" name="hire_date" class="w-full border rounded px-3 py-2 bg-gray-50"
                        value="{{ old('hire_date', $employee->hire_date ?? '') }}" readonly>
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

            <h3 class="font-semibold text-lg mt-6 mb-3">Security</h3>            <div class="mb-4">
                <label class="block mb-1 font-semibold">
                    Password 
                    <span class="text-gray-500 text-xs">(leave blank to keep current)</span>
                </label>
                <div class="relative">
                    <input class="w-full border rounded px-3 py-2 pr-10 @error('password') border-red-500 @enderror"
                           type="password"
                           name="password"
                           id="password">
                    <div id="togglePassword" 
                         class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 cursor-pointer">
                        <svg class="h-5 w-5 eye-closed" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg class="h-5 w-5 eye-open hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </div>
                </div>
                @error('password')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block mb-1 font-semibold">
                    Confirm Password 
                    <span class="text-gray-500 text-xs">(required if changing password)</span>
                </label>
                <div class="relative">
                    <input class="w-full border rounded px-3 py-2 pr-10 @error('password_confirmation') border-red-500 @enderror"
                           type="password"
                           name="password_confirmation"
                           id="password_confirmation">
                    <div id="togglePasswordConfirmation" 
                         class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 cursor-pointer">
                        <svg class="h-5 w-5 eye-closed" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg class="h-5 w-5 eye-open hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </div>
                </div>
                @error('password_confirmation')
                    <div class="text-red-600 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="flex justify-between items-center">
                <button type="submit"
                    class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">Update
                    Profile</button>
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
            </div>
        </form>    </div>
@endsection

@push('scripts')    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to setup password toggle
            function setupPasswordToggle(toggleId, passwordId) {
                const toggle = document.getElementById(toggleId);
                const password = document.getElementById(passwordId);
                const eyeOpen = toggle.querySelector('.eye-open');
                const eyeClosed = toggle.querySelector('.eye-closed');

                toggle.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                });

                toggle.addEventListener('click', function(e) {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    eyeOpen.classList.toggle('hidden');
                    eyeClosed.classList.toggle('hidden');
                    password.focus();
                });
            }

            // Setup toggle for both password fields
            setupPasswordToggle('togglePassword', 'password');
            setupPasswordToggle('togglePasswordConfirmation', 'password_confirmation');
        });
    </script>
@endpush




