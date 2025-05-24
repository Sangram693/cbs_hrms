@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Trainings</h2>
    <a href="{{ route('trainings.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Training</a>
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="border-b border-gray-300 bg-gray-100">
                <th class="py-2 px-4 text-center border-r border-gray-300">Employee</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Training Name</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Status</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Start Date</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">End Date</th>
                @if(auth()->user()->isSuperAdmin())
                <th class="py-2 px-4 text-center border-r border-gray-300">Company</th>
                @endif
                <th class="py-2 px-4 text-center border-r border-gray-300">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trainings as $training)
            <tr class="border-b border-gray-300">
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $training->employee->name ?? '-' }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $training->training_name ?? $training->title }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $training->status }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $training->start_date ?? $training->date }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $training->end_date ?? '-' }}</td>
                @if(auth()->user()->isSuperAdmin())
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $training->employee && $training->employee->company ? $training->employee->company->name : '-' }}</td>
                @endif
                <td class="py-2 px-4 text-center border-r border-gray-300">
                    <a href="{{ route('trainings.edit', $training) }}" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="{{ route('trainings.destroy', $training) }}" method="POST" class="inline" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 bg-transparent border-none p-0 m-0 cursor-pointer" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-2 px-4 text-center border-t border-gray-300">No training records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
