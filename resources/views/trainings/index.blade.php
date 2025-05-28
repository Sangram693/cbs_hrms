@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-7xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Trainings</h2>
    <a href="{{ route('trainings.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Training</a>
    <div class="relative overflow-auto shadow-md" style="height: calc(100vh - 250px);">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0">
                <tr>
                    <th class="py-3 px-6 text-center">Employee</th>
                    <th class="py-3 px-6 text-center">Training Name</th>
                    <th class="py-3 px-6 text-center">Status</th>
                    <th class="py-3 px-6 text-center">Start Date</th>
                    <th class="py-3 px-6 text-center">End Date</th>
                    @if(auth()->user()->isSuperAdmin())
                    <th class="py-3 px-6 text-center">Company</th>
                    @endif
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainings as $training)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="py-3 px-6 text-center whitespace-nowrap">{{ $training->employee->name ?? '-' }}</td>
                    <td class="py-3 px-6 text-center whitespace-nowrap">{{ $training->training_name ?? $training->title }}</td>
                    <td class="py-3 px-6 text-center whitespace-nowrap">{{ $training->status }}</td>
                    <td class="py-3 px-6 text-center whitespace-nowrap">{{ $training->start_date ?? $training->date }}</td>
                    <td class="py-3 px-6 text-center whitespace-nowrap">{{ $training->end_date ?? '-' }}</td>
                    @if(auth()->user()->isSuperAdmin())
                    <td class="py-3 px-6 text-center whitespace-nowrap">{{ $training->employee && $training->employee->company ? $training->employee->company->name : '-' }}</td>
                    @endif
                    <td class="py-3 px-6 text-center whitespace-nowrap">
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
                <tr class="bg-white border-b">
                    <td colspan="7" class="py-3 px-6 text-center">No training records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
