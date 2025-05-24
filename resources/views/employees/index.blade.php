@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Employees</h2>
    <a href="{{ route('employees.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Employee</a>
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="border-b border-gray-300 bg-gray-100">
                <th class="py-2 px-4 text-center border-r border-gray-300">Name</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Email</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Department</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Position</th>
                @if(auth()->user()->isSuperAdmin())
                <th class="py-2 px-4 text-center border-r border-gray-300">Company</th>
                @endif
                <th class="py-2 px-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
            <tr class="border-b border-gray-300">
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $employee->name }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $employee->email }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $employee->department->name ?? '-' }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $employee->position->title }}({{ $employee->position->level }})</td>
                @if(auth()->user()->isSuperAdmin())
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $employee->company->name ?? '-' }}</td>
                @endif
                <td class="py-2 px-4 text-center">
                    <a href="{{ route('employees.edit', $employee) }}" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this employee?');" style="display:inline">
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
                <td colspan="6" class="py-2 px-4 text-center border-t border-gray-300">No employees found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
