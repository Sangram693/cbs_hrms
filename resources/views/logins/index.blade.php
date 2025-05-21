@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Logins</h2>
    <a href="{{ route('logins.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Login</a>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2">Employee</th>
                <th class="py-2">Username</th>
                <th class="py-2">Role</th>
                <th class="py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logins as $login)
            <tr>
                <td class="py-2">{{ $login->employee->name ?? '-' }}</td>
                <td class="py-2">{{ $login->username }}</td>
                <td class="py-2">{{ $login->role }}</td>
                <td class="py-2">
                    <a href="{{ route('logins.edit', $login) }}" class="text-blue-600 hover:underline">Edit</a> |
                    <form action="{{ route('logins.destroy', $login) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline bg-transparent border-none p-0 m-0 cursor-pointer">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-2 text-center">No login records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
