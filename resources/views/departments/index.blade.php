@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Departments</h2>
    @php
        $isHr = auth()->user()->role === 'user' && auth()->user()->employee && \App\Models\Department::where('hr_id', auth()->user()->employee->id)->exists();
    @endphp
    @if(!$isHr)
    <a href="{{ route('departments.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Department</a>
    @endif
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 text-center">Name</th>
                @if(auth()->user()->isSuperAdmin())
                <th class="py-2 px-4 text-center">Company</th>
                @endif
                <th class="py-2 px-4 text-center">HR (Employee)</th>
                @if(!$isHr)
                <th class="py-2 px-4 text-center">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($departments as $department)
            <tr>
                <td class="py-2 px-4 text-center">{{ $department->name }}</td>
                @if(auth()->user()->isSuperAdmin())
                <td class="py-2 px-4 text-center">{{ $department->company->name ?? '-' }}</td>
                @endif
                <td class="py-2 px-4 text-center">{{ $department->hr ? $department->hr->name . ' (' . $department->hr->email . ')' : '-' }}</td>
                @if(!$isHr)
                <td class="py-2 px-4 text-center">
                    <a href="{{ route('departments.edit', $department) }}" class="text-blue-600 hover:underline">Edit</a> |
                    <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline bg-transparent border-none p-0 m-0 cursor-pointer">Delete</button>
                    </form>
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-2 px-4 text-center">No departments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
