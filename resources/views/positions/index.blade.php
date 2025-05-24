@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Positions</h2>
    <a href="{{ route('positions.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Position</a>
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="border-b border-gray-300 bg-gray-100">
                <th class="py-2 px-4 text-center border-r border-gray-300">Title</th>
                @if(auth()->user()->isSuperAdmin())
                <th class="py-2 px-4 text-center border-r border-gray-300">Company</th>
                @endif
                <th class="py-2 px-4 text-center border-r border-gray-300">Department</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($positions as $position)
            <tr class="border-b border-gray-300">
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $position->title }}({{ $position->level }})</td>
                @if(auth()->user()->isSuperAdmin())
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $position->company->name ?? '-' }}</td>
                @endif
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $position->department->name ?? '-' }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">
                    <a href="{{ route('positions.edit', $position) }}" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="{{ route('positions.destroy', $position) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this position?');" style="display:inline">
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
                <td colspan="2" class="py-2 px-4 text-center border-t border-gray-300">No positions found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
