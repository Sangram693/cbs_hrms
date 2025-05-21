@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Trainings</h2>
    <a href="{{ route('trainings.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Training</a>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2">Employee</th>
                <th class="py-2">Training Name</th>
                <th class="py-2">Status</th>
                <th class="py-2">Start Date</th>
                <th class="py-2">End Date</th>
                @if(auth()->user()->isSuperAdmin())
                <th class="py-2">Company</th>
                @endif
                <th class="py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trainings as $training)
            <tr>
                <td class="py-2">{{ $training->employee->name ?? '-' }}</td>
                <td class="py-2">{{ $training->training_name ?? $training->title }}</td>
                <td class="py-2">{{ $training->status }}</td>
                <td class="py-2">{{ $training->start_date ?? $training->date }}</td>
                <td class="py-2">{{ $training->end_date ?? '-' }}</td>
                @if(auth()->user()->isSuperAdmin())
                <td class="py-2">{{ $training->employee && $training->employee->company ? $training->employee->company->name : '-' }}</td>
                @endif
                <td class="py-2">
                    <a href="{{ route('trainings.edit', $training) }}" class="text-blue-600 hover:underline">Edit</a> |
                    <form action="{{ route('trainings.destroy', $training) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline bg-transparent border-none p-0 m-0 cursor-pointer">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-2 text-center">No training records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
