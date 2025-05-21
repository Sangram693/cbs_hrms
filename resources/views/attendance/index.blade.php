@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Attendance</h2>
    <a href="{{ route('attendance.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Attendance</a>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2">Employee</th>
                <th class="py-2">Date</th>
                <th class="py-2">Check In</th>
                <th class="py-2">Check Out</th>
                <th class="py-2">Status</th>
                @if(auth()->user()->isSuperAdmin())
                <th class="py-2">Company</th>
                @endif
                <th class="py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
            <tr>
                <td class="py-2">{{ $attendance->employee->name ?? '-' }}</td>
                <td class="py-2">{{ $attendance->date }}</td>
                <td class="py-2">{{ $attendance->check_in }}</td>
                <td class="py-2">{{ $attendance->check_out }}</td>
                <td class="py-2">{{ $attendance->status }}</td>
                @if(auth()->user()->isSuperAdmin())
                <td class="py-2">{{ $attendance->employee->company->name ?? '-' }}</td>
                @endif
                <td class="py-2">
                    <a href="{{ route('attendance.edit', $attendance) }}" class="text-blue-600 hover:underline">Edit</a> |
                    <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline bg-transparent border-none p-0 m-0 cursor-pointer">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-2 text-center">No attendance records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
