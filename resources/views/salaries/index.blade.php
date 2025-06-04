@extends('layouts.app')
@section('title', 'Salaries')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-7xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Salaries</h2>
    <a href="{{ route('salaries.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Salary</a>
    <div class="relative overflow-auto shadow-md" style="height: calc(100vh - 250px);">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0">
                <tr>
                    <th class="py-3 px-6 text-center">Employee</th>
                    <th class="py-3 px-6 text-center">Month</th>
                    <th class="py-3 px-6 text-center">Net Salary</th>
                    <th class="py-3 px-6 text-center">Paid On</th>
                    @if(auth()->user()->isSuperAdmin())
                    <th class="py-3 px-6 text-center">Company</th>
                    @endif
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salaries as $salary)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="py-3 px-6 text-center whitespace-nowrap">{{ $salary->employee->name ?? '-' }}</td>
                    <td class="py-3 px-6 text-center whitespace-nowrap">{{ \Illuminate\Support\Str::substr($salary->date ?? $salary->salary_month, 0, 7) }}</td>
                    <td class="py-3 px-6 text-center whitespace-nowrap">{{ $salary->net_salary ?? $salary->amount }}</td>
                    <td class="py-3 px-6 text-center whitespace-nowrap">{{ $salary->paid_on ?? ($salary->date ?? '-') }}</td>
                    @if(auth()->user()->isSuperAdmin())
                    <td class="py-3 px-6 text-center whitespace-nowrap">{{ $salary->employee && $salary->employee->company ? $salary->employee->company->name : '-' }}</td>
                    @endif
                    <td class="py-3 px-6 text-center whitespace-nowrap">
                        <a href="{{ route('salaries.edit', $salary->id) }}" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('salaries.destroy', $salary->id) }}" method="POST" class="inline" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 bg-transparent border-none p-0 m-0 cursor-pointer" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr class="bg-white border-b">
                    <td colspan="6" class="py-3 px-6 text-center">No salary records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
