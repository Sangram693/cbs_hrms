@extends('layouts.app')
@section('title', 'Edit Company')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Company</h2>
    <form action="{{ route('companies.update', $company) }}" method="POST">
        @csrf
        @method('PUT')        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Name</span>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input type="text" 
                name="name" 
                class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror" 
                value="{{ old('name', $company->name) }}">
            @error('name')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Address</span>
                <span class="text-gray-500 text-sm ml-1">(Optional)</span>
            </label>
            <textarea 
                name="address" 
                class="w-full border rounded px-3 py-2 @error('address') border-red-500 @enderror" 
                rows="2">{{ old('address', $company->address) }}</textarea>
            @error('address')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1">
                <span class="font-semibold">Phone</span>
                <span class="text-gray-500 text-sm ml-1">(Optional)</span>
            </label>
            <input type="tel" 
                name="phone" 
                class="w-full border rounded px-3 py-2 @error('phone') border-red-500 @enderror" 
                value="{{ old('phone', $company->phone) }}"
                placeholder="e.g., +1234567890">
            @error('phone')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Update</button>
        <a href="{{ route('companies.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">Cancel</a>
    </form>
</div>
@endsection
