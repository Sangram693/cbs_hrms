@extends('layouts.app')
@section('title', 'Departments')
@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Departments</h2>
    @php
        $user = auth()->user();
        $isHr = $user->isHr();
    @endphp
    @if(!$isHr)
    <a href="{{ route('departments.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Department</a>
    @endif
    <div class="relative overflow-auto shadow-md" style="height: calc(100vh - 250px);">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>                <th class="py-3 px-6 text-center">Name</th>
                @if(auth()->user()->isSuperAdmin())
                <th class="py-3 px-6 text-center">Company</th>
                @endif
                <th class="py-3 px-6 text-center">HR (Employee)</th>
                @if(!$isHr)
                <th class="py-3 px-6 text-center">Actions</th>
                @endif
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($departments as $department)            <tr class="hover:bg-gray-50">
                <td class="py-3 px-6 text-center whitespace-nowrap">{{ $department->name }}</td>
                @if(auth()->user()->isSuperAdmin())
                <td class="py-3 px-6 text-center whitespace-nowrap">{{ $department->company->name ?? '-' }}</td>
                @endif
                <td class="py-3 px-6 text-center whitespace-nowrap">{{ $department->hr ? $department->hr->name . ' (' . $department->hr->email . ')' : '-' }}</td>
                @if(!$isHr)
                <td class="py-3 px-6 text-center whitespace-nowrap">
                    <div class="flex items-center justify-center space-x-3">
                        <a href="{{ route('departments.edit', $department) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this department?');">
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
            @empty            <tr>
                <td colspan="4" class="py-3 px-6 text-center">No departments found.</td>
            </tr>
            @endforelse
        </tbody>
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
