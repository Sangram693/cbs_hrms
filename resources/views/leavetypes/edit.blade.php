@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Leave Type</h2>
    <form action="{{ route('leavetypes.update', $leavetype) }}" method="POST">
        @csrf
        @method('PUT')
        @php $user = auth()->user(); @endphp
        @if($user->isSuperAdmin())
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Company</label>
                <select name="company_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ $leavetype->company_id == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
                @error('company_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
        @endif
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Leave Type Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name', $leavetype->name) }}" required>
            @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('leavetypes.index', $user->isSuperAdmin() ? ['company_id'=>$leavetype->company_id] : []) }}" class="ml-2 text-gray-600">Cancel</a>
    </form>
</div>
@endsection
