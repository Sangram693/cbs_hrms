@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Salaries</h2>
    <a href="{{ route('salaries.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Salary</a>
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="border-b border-gray-300 bg-gray-100">
                <th class="py-2 px-4 text-center border-r border-gray-300">Employee</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Month</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Net Salary</th>
                <th class="py-2 px-4 text-center border-r border-gray-300">Paid On</th>
                @if(auth()->user()->isSuperAdmin())
                <th class="py-2 px-4 text-center border-r border-gray-300">Company</th>
                @endif
                <th class="py-2 px-4 text-center border-r border-gray-300">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($salaries as $salary)
            <tr class="border-b border-gray-300">
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $salary->employee->name ?? '-' }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ \Illuminate\Support\Str::substr($salary->date ?? $salary->salary_month, 0, 7) }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $salary->net_salary ?? $salary->amount }}</td>
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $salary->paid_on ?? ($salary->date ?? '-') }}</td>
                @if(auth()->user()->isSuperAdmin())
                <td class="py-2 px-4 text-center border-r border-gray-300">{{ $salary->employee && $salary->employee->company ? $salary->employee->company->name : '-' }}</td>
                @endif
                <td class="py-2 px-4 text-center border-r border-gray-300">
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
            <tr>
                <td colspan="5" class="py-2 px-4 text-center border-t border-gray-300">No salary records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
