@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Employees</h2>
    <a href="{{ route('employees.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Employee</a>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 text-center">Name</th>
                <th class="py-2 px-4 text-center">Email</th>
                <th class="py-2 px-4 text-center">Department</th>
                <th class="py-2 px-4 text-center">Position</th>
                @if(auth()->user()->isSuperAdmin())
                <th class="py-2 px-4 text-center">Company</th>
                @endif
                <th class="py-2 px-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
            <tr>
                <td class="py-2 px-4 text-center">{{ $employee->name }}</td>
                <td class="py-2 px-4 text-center">{{ $employee->email }}</td>
                <td class="py-2 px-4 text-center">{{ $employee->department->name ?? '-' }}</td>
                <td class="py-2 px-4 text-center">{{ $employee->position->title }}({{ $employee->position->level }})</td>
                @if(auth()->user()->isSuperAdmin())
                <td class="py-2 px-4 text-center">{{ $employee->company->name ?? '-' }}</td>
                @endif
                <td class="py-2 px-4 text-center">
                    <a href="{{ route('employees.edit', $employee) }}" class="text-blue-600 hover:underline">Edit</a> |
                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline bg-transparent border-none p-0 m-0 cursor-pointer">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-2 px-4 text-center">No employees found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
