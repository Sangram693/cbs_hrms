@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Leave Types</h2>
    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif
    @php $user = auth()->user(); @endphp
    <div class="mb-4 flex flex-row justify-between items-center">
        <a href="{{ route('leavetypes.create', $user->isSuperAdmin() ? ['company_id'=>request('company_id')] : []) }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add Leave Type</a>
        <div>
            @if($user->isSuperAdmin())
                <form method="GET" action="{{ route('leavetypes.index') }}" class="inline">
                    <select name="company_id" onchange="this.form.submit()" class="border rounded px-2 py-1">
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>
    </div>
    <div class="relative overflow-auto shadow-md" style="height: calc(100vh - 250px);">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0">
                <tr>
                    <th class="py-3 px-6 text-center">SL</th>
                    <th class="py-3 px-6 text-center">Name</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaveTypes as $type)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="py-3 px-6 text-center whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="py-3 px-6 text-center whitespace-nowrap">{{ $type->name }}</td>
                        <td class="py-3 px-6 text-center whitespace-nowrap">
                            <a href="{{ route('leavetypes.edit', $type) }}" class="text-blue-600 mr-2"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('leavetypes.destroy', $type) }}" method="POST" class="inline" onsubmit="return confirm('Delete this leave type?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b">
                        <td colspan="3" class="py-3 px-6 text-center">No leave types found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
