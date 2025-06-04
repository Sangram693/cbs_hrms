@extends('layouts.app')
@section('title', 'Designations')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-7xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Designations</h2>
    <a href="{{ route('designations.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Designation</a>
    <div class="relative overflow-auto shadow-md" style="height: calc(100vh - 250px);">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0">
                <tr>
                    <th class="py-3 px-6 text-center">Title</th>
                    <th class="py-3 px-6 text-center">Level</th>
                    @if(auth()->user()->isSuperAdmin())
                    <th class="py-3 px-6 text-center">Company</th>
                    @endif
                    <th class="py-3 px-6 text-center">Department</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($designations as $designation)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="py-3 px-6 text-center whitespace-nowrap">{{ $designation->title }}</td>
                    <td class="py-3 px-6 text-center whitespace-nowrap">{{ $designation->level }}</td>
                    @if(auth()->user()->isSuperAdmin())
                    <td class="py-3 px-6 text-center whitespace-nowrap">{{ $designation->company->name ?? '-' }}</td>
                    @endif
                    <td class="py-3 px-6 text-center whitespace-nowrap">{{ $designation->department->name ?? '-' }}</td>
                    <td class="py-3 px-6 text-center whitespace-nowrap">
                        <a href="{{ route('designations.edit', $designation) }}" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('designations.destroy', $designation) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this designation?');" style="display:inline">
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
                    <td colspan="5" class="py-3 px-6 text-center">No designations found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
