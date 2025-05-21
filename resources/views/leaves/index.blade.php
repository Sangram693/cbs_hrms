@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Leaves</h2>
    <a href="{{ route('leaves.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Leave</a>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2">Employee</th>
                <th class="py-2">Type</th>
                <th class="py-2">From</th>
                <th class="py-2">To</th>
                <th class="py-2">Status</th>
                @if(auth()->user()->isSuperAdmin())
                <th class="py-2">Company</th>
                @endif
                <th class="py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaves as $leave)
            <tr>
                <td class="py-2">
                    {{ $leave->employee ? $leave->employee->name : 'No employee found' }}
                </td>
                <td class="py-2">{{ $leave->leave_type }}</td>
                <td class="py-2">{{ $leave->start_date }}</td>
                <td class="py-2">{{ $leave->end_date }}</td>
                <td class="py-2">{{ $leave->status }}</td>
                @if(auth()->user()->isSuperAdmin())
                <td class="py-2">{{ $leave->employee && $leave->employee->company ? $leave->employee->company->name : '-' }}</td>
                @endif
                <td class="py-2">
                    <a href="{{ route('leaves.edit', $leave) }}" class="text-blue-600 hover:underline">Edit</a> |
                    <form action="{{ route('leaves.destroy', $leave) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline bg-transparent border-none p-0 m-0 cursor-pointer">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-2 text-center">No leave records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
