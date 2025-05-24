@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Attendance</h2>
    @php
        $user = auth()->user();
        $isHr = $user->isHr();
    @endphp
    @if($user->isSuperAdmin() || $user->isAdmin() || $isHr)
    <a href="{{ route('attendance.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Attendance</a>
    @endif
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="border-b border-gray-300 bg-gray-100">
                <th class="py-2 px-4 text-center border-r border-gray-300">Employee</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Date</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Check In</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Check Out</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Status</th>
                @if($user->isSuperAdmin())
                <th class="py-2 px-4 text-center border-r border-gray-300">Company</th>
                @endif
                @if($user->isSuperAdmin() || $user->isAdmin() || $isHr)
                <th class="py-2 px-4 text-center border-r border-gray-300">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
            <tr class="border-b border-gray-300">
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $attendance->employee->name ?? '-' }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $attendance->date }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $attendance->check_in }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $attendance->check_out }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $attendance->status }}</td>
                @if($user->isSuperAdmin())
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $attendance->employee->company->name ?? '-' }}</td>
                @endif
                @if($user->isSuperAdmin() || $user->isAdmin() || $isHr)
                <td class="py-2 px-4 text-center border-r border-gray-300">
                    <a href="{{ route('attendance.edit', $attendance) }}" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this attendance record?');" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 bg-transparent border-none p-0 m-0 cursor-pointer" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ 6 + ($user->isSuperAdmin() ? 1 : 0) + (($user->isSuperAdmin() || $user->isAdmin() || $isHr) ? 1 : 0) }}" class="py-2 px-4 text-center border-t border-gray-300">No attendance records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection