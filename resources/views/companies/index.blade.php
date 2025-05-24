@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Companies</h2>
    @if(auth()->user()->isSuperAdmin())
    <a href="{{ route('companies.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Company</a>
    @endif
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="border-b border-gray-300 bg-gray-100">
                <th class="py-2 px-4 text-center border-r border-gray-300">Name</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Address</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Phone</th>
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <th class="py-2 px-4 text-center border-r border-gray-300">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($companies as $company)
            <tr class="border-b border-gray-300">
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $company->name }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $company->address }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $company->phone }}</td>
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <td class="py-2 px-4 text-center border-r border-gray-300">
                    <a href="{{ route('companies.edit', $company) }}" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    @if(auth()->user()->isSuperAdmin())
                    <form action="{{ route('companies.destroy', $company) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this company?');" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 bg-transparent border-none p-0 m-0 cursor-pointer" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()) ? 4 : 3 }}" class="py-2 px-4 text-center border-t border-gray-300">No companies found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
