@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Salaries</h2>
    <a href="{{ route('salaries.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Salary</a>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 text-center">Employee</th>
                <th class="py-2 px-4 text-center">Month</th>
                <th class="py-2 px-4 text-center">Net Salary</th>
                <th class="py-2 px-4 text-center">Paid On</th>
                @if(auth()->user()->isSuperAdmin())
                <th class="py-2 px-4 text-center">Company</th>
                @endif
                <th class="py-2 px-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($salaries as $salary)
            <tr>
                <td class="py-2 px-4 text-center">{{ $salary->employee->name ?? '-' }}</td>
                <td class="py-2 px-4 text-center">{{ \Illuminate\Support\Str::substr($salary->date ?? $salary->salary_month, 0, 7) }}</td>
                <td class="py-2 px-4 text-center">{{ $salary->net_salary ?? $salary->amount }}</td>
                <td class="py-2 px-4 text-center">{{ $salary->paid_on ?? ($salary->date ?? '-') }}</td>
                @if(auth()->user()->isSuperAdmin())
                <td class="py-2 px-4 text-center">{{ $salary->employee && $salary->employee->company ? $salary->employee->company->name : '-' }}</td>
                @endif
                <td class="py-2 px-4 text-center">
                    <a href="{{ route('salaries.edit', $salary->id) }}" class="text-blue-600 hover:underline">Edit</a> |
                    <form action="{{ route('salaries.destroy', $salary->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline bg-transparent border-none p-0 m-0 cursor-pointer">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-2 px-4 text-center">No salary records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
