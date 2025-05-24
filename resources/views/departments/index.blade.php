@extends('layouts.app')

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
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="border-b border-gray-300 bg-gray-100">
                <th class="py-2 px-4 text-center border-r border-gray-300">Name</th>
                @if(auth()->user()->isSuperAdmin())
                <th class="py-2 px-4 text-center border-r border-gray-300">Company</th>
                @endif
                <th class="py-2 px-4 text-center border-r border-gray-300">HR (Employee)</th>
                @if(!$isHr)
                <th class="py-2 px-4 text-center border-r border-gray-300">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($departments as $department)
            <tr class="border-b border-gray-300">
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $department->name }}</td>
                @if(auth()->user()->isSuperAdmin())
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $department->company->name ?? '-' }}</td>
                @endif
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $department->hr ? $department->hr->name . ' (' . $department->hr->email . ')' : '-' }}</td>
                @if(!$isHr)
                <td class="py-2 px-4 text-center border-r border-gray-300">
                    <a href="{{ route('departments.edit', $department) }}" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this department?');" style="display:inline">
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
                <td colspan="4" class="py-2 px-4 text-center border-t border-gray-300">No departments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
