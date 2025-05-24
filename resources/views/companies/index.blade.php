@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Companies</h2>
    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
    <a href="{{ route('companies.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Company</a>
    @endif
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 text-center">Name</th>
                <th class="py-2 px-4 text-center">Address</th>
                <th class="py-2 px-4 text-center">Phone</th>
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <th class="py-2 px-4 text-center">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($companies as $company)
            <tr>
                <td class="py-2 px-4 text-center">{{ $company->name }}</td>
                <td class="py-2 px-4 text-center">{{ $company->address }}</td>
                <td class="py-2 px-4 text-center">{{ $company->phone }}</td>
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <td class="py-2 px-4 text-center">
                    <a href="{{ route('companies.edit', $company) }}" class="text-blue-600 hover:underline">Edit</a>
                    @if(auth()->user()->isSuperAdmin())
                    |
                    <form action="{{ route('companies.destroy', $company) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this company?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline bg-transparent border-none p-0 m-0 cursor-pointer">Delete</button>
                    </form>
                    @endif
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()) ? 4 : 3 }}" class="py-2 px-4 text-center">No companies found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
