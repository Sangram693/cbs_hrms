@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Departments</h2>
    <a href="{{ route('departments.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Department</a>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2">Name</th>
                @if(auth()->user()->isSuperAdmin())
                <th class="py-2">Company</th>
                @endif
                <th class="py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($departments as $department)
            <tr>
                <td class="py-2">{{ $department->name }}</td>
                @if(auth()->user()->isSuperAdmin())
                <td class="py-2">{{ $department->company->name ?? '-' }}</td>
                @endif
                <td class="py-2">
                    <a href="{{ route('departments.edit', $department) }}" class="text-blue-600 hover:underline">Edit</a> |
                    <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline bg-transparent border-none p-0 m-0 cursor-pointer">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="py-2 text-center">No departments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
