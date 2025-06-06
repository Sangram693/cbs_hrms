@extends('layouts.app')
@section('title', 'Upload Bill')
@section('content')
    <div class="space-y-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Upload Bill</h2>
            <form action="{{ route('bills.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>                        <label class="block text-sm font-medium text-gray-700">Bill Type</label>
                        <select name="bill_type" required class="w-full border rounded px-3 py-2">
                            <option value="TA">Travel Allowance</option>
                            <option value="FA">Food Allowance</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div>                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                        <input type="number" name="amount" step="0.01" required class="w-full border rounded px-3 py-2">
                    </div>

                    <div>                        <label class="block text-sm font-medium text-gray-700">Bill Date</label>
                        <input type="date" name="bill_date" required class="w-full border rounded px-3 py-2">
                    </div>

                    <div>                        <label class="block text-sm font-medium text-gray-700">Bill File (PDF/Image)</label>
                        <input type="file" name="bill_file" required accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="md:col-span-2">                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="2" class="w-full border rounded px-3 py-2"></textarea>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Upload Bill
                    </button>
                    <a href="{{ route('salaries.index') }}" class="text-gray-600 hover:text-gray-800">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
