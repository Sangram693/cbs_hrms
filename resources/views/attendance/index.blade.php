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
    @endif    <div class="relative overflow-auto shadow-md" style="height: calc(100vh - 250px);">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                <th class="py-3 px-6 text-center">Employee</th>
                <th class="py-3 px-6 text-center">Date</th>
                <th class="py-3 px-6 text-center">Check In</th>
                <th class="py-3 px-6 text-center">Check Out</th>
                <th class="py-3 px-6 text-center">Status</th>
                @if($user->isSuperAdmin())
                <th class="py-3 px-6 text-center">Company</th>
                @endif
                @if($user->isSuperAdmin() || $user->isAdmin() || $isHr)
                <th class="py-3 px-6 text-center">Actions</th>
                @endif
            </tr>
            </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($attendances as $attendance)            <tr class="hover:bg-gray-50">
                <td class="py-3 px-6 text-center whitespace-nowrap">{{ $attendance->employee->name ?? '-' }}</td>
                <td class="py-3 px-6 text-center whitespace-nowrap">{{ $attendance->date }}</td>
                <td class="py-3 px-6 text-center whitespace-nowrap">{{ $attendance->check_in }}</td>
                <td class="py-3 px-6 text-center whitespace-nowrap">{{ $attendance->check_out }}</td>
                <td class="py-3 px-6 text-center whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $attendance->status === 'Present' ? 'bg-green-100 text-green-800' : 
                           ($attendance->status === 'Absent' ? 'bg-red-100 text-red-800' : 
                           'bg-yellow-100 text-yellow-800') }}">
                        {{ $attendance->status }}
                    </span>
                </td>                @if($user->isSuperAdmin())
                <td class="py-3 px-6 text-center whitespace-nowrap">{{ $attendance->employee->company->name ?? '-' }}</td>
                @endif
                @if($user->isSuperAdmin() || $user->isAdmin() || $isHr)
                <td class="py-3 px-6 text-center whitespace-nowrap">
                    <div class="flex items-center justify-center space-x-3">
                        <a href="{{ route('attendance.edit', $attendance) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this attendance record?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 bg-transparent border-none p-0 cursor-pointer" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
                @endif
            </tr>
            @empty
            <tr>                <td colspan="{{ 6 + ($user->isSuperAdmin() ? 1 : 0) + (($user->isSuperAdmin() || $user->isAdmin() || $isHr) ? 1 : 0) }}" class="py-2 px-4 text-center border-t border-gray-300">No attendance records found.</td>
            </tr>
            @endforelse        </tbody>
    </table>
    </div>
</div>

<style>
    thead {
        position: sticky;
        top: 0;
        z-index: 1;
    }
    
    .relative {
        background: white;
        border-radius: 0.5rem;
    }

    table {
        border-collapse: separate;
        border-spacing: 0;
    }

    th {
        border-bottom: 2px solid #e5e7eb;
        background-color: #f9fafb;
        font-weight: 600;
        text-transform: uppercase;
    }

    tr:hover {
        background-color: #f9fafb;
    }

    td {
        border-bottom: 1px solid #e5e7eb;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }
</style>
@endsection