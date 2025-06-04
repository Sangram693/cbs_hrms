@extends('layouts.app')
@section('title', 'Company')
@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Companies</h2>
    @if(auth()->user()->isSuperAdmin())
    <a href="{{ route('companies.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Company</a>
    @endif
    <div class="relative overflow-auto shadow-md" style="height: calc(100vh - 250px);">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>                <th class="py-3 px-6 text-center">Name</th>
                <th class="py-3 px-6 text-center">Address</th>
                <th class="py-3 px-6 text-center">Phone</th>
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <th class="py-3 px-6 text-center">Actions</th>
                @endif
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($companies as $company)            <tr class="hover:bg-gray-50">
                <td class="py-3 px-6 text-center whitespace-nowrap">{{ $company->name }}</td>
                <td class="py-3 px-6 text-center whitespace-nowrap">{{ $company->address }}</td>
                <td class="py-3 px-6 text-center whitespace-nowrap">{{ $company->phone }}</td>
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <td class="py-3 px-6 text-center whitespace-nowrap">
                    <div class="flex items-center justify-center space-x-3">
                        <a href="{{ route('companies.edit', $company) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        @if(auth()->user()->isSuperAdmin())
                        <form action="{{ route('companies.destroy', $company) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this company?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 bg-transparent border-none p-0 cursor-pointer" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
                @endif
            </tr>
            @empty            <tr>
                <td colspan="{{ (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()) ? 4 : 3 }}" class="py-3 px-6 text-center">No companies found.</td>
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
