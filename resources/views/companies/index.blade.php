@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Companies</h2>
    @if(auth()->user()->isSuperAdmin())
    <a href="{{ route('companies.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Company</a>
    @endif
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2">Name</th>
                <th class="py-2">Address</th>
                <th class="py-2">Phone</th>
                @if(auth()->user()->isSuperAdmin())
                <th class="py-2">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($companies as $company)
            <tr>
                <td class="py-2">{{ $company->name }}</td>
                <td class="py-2">{{ $company->address }}</td>
                <td class="py-2">{{ $company->phone }}</td>
                @if(auth()->user()->isSuperAdmin())
                <td class="py-2">
                    <a href="{{ route('companies.edit', $company) }}" class="text-blue-600 hover:underline">Edit</a> |
                    <form action="{{ route('companies.destroy', $company) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline bg-transparent border-none p-0 m-0 cursor-pointer">Delete</button>
                    </form>
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ auth()->user()->isSuperAdmin() ? 4 : 3 }}" class="py-2 text-center">No companies found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
