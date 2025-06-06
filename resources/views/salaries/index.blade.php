@extends('layouts.app')
@section('title', 'Salaries')
@section('content')
    <div class="space-y-8">
        <!-- Salaries Section -->
        <div class="bg-white p-6 rounded shadow max-w-7xl mx-auto">
            <h2 class="text-xl font-bold mb-4">Salaries</h2>
            @if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isHr())
                <a href="{{ route('salaries.create') }}"
                    class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add Salary</a>
            @endif
            <div class="relative overflow-auto shadow-md" style="height: calc(30vh);">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0">
                        <tr>
                            <th class="py-3 px-6 text-center">Employee</th>
                            <th class="py-3 px-6 text-center">Month</th>
                            <th class="py-3 px-6 text-center">Net Salary</th>
                            <th class="py-3 px-6 text-center">Paid On</th>
                            @if (auth()->user()->isSuperAdmin())
                                <th class="py-3 px-6 text-center">Company</th>
                            @endif
                            <th class="py-3 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaries as $salary)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="py-3 px-6 text-center whitespace-nowrap">{{ $salary->employee->name ?? '-' }}
                                </td>
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    {{ \Illuminate\Support\Str::substr($salary->date ?? $salary->salary_month, 0, 7) }}</td>
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    {{ $salary->net_salary ?? $salary->amount }}</td>
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    {{ $salary->paid_on ?? ($salary->date ?? '-') }}</td>
                                @if (auth()->user()->isSuperAdmin())
                                    <td class="py-3 px-6 text-center whitespace-nowrap">
                                        {{ $salary->employee && $salary->employee->company ? $salary->employee->company->name : '-' }}
                                    </td>
                                @endif
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    @if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isHr())
                                        <a href="{{ route('salaries.edit', $salary->id) }}"
                                            class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('salaries.destroy', $salary->id) }}" method="POST"
                                            class="inline" style="display:inline">
                                            @csrf
                                            @method('DELETE') <button type="submit"
                                                class="text-red-600 hover:text-red-800 bg-transparent border-none p-0 m-0 cursor-pointer"
                                                title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400">No actions available</span>
                                    @endif
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

        <!-- My Bills List -->
        @if (auth()->user()->isUser())
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">My Bills</h2>
                    <a href="{{ route('bills.upload') }}" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Upload New Bill
                    </a>
                </div>
                <div class="overflow-x-auto shadow-md" style="height: calc(30vh);">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                    Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($bills ?? [] as $bill)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $bill->bill_date->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $bill->bill_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($bill->amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($bill->status === 'rejected')
                                            <button type="button" 
                                                onclick="showRejectionReasonModal('{{ $bill->rejection_reason }}')"
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 hover:bg-red-200 cursor-pointer">
                                                Rejected
                                            </button>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $bill->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($bill->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ Storage::url($bill->file_path) }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        @if ($bill->status === 'pending')
                                            <form action="{{ route('bills.destroy', $bill) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        @endif
                                        <!-- Removed View Reason button as status is now clickable -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Admin Bill Review Section -->
        @if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin() || auth()->user()->isHr())
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Review Bills</h2>
                <div class="overflow-x-auto" style="max-height: 400px;">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                    Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($pendingBills ?? [] as $bill)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $bill->employee->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $bill->bill_date->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $bill->bill_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($bill->amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ Storage::url($bill->file_path) }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        <form action="{{ route('bills.update-status', $bill) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit"
                                                class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                        </form>
                                        <button onclick="showRejectModal('{{ $bill->id }}')"
                                            class="text-red-600 hover:text-red-900">Reject</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Reject Bill</h3>
                <div class="mt-2 px-7 py-3">
                    <form id="rejectForm" action="" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <label class="block text-sm font-medium text-gray-700">Reason for Rejection</label>
                        <textarea name="rejection_reason" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        <div class="flex justify-end mt-4 gap-3">
                            <button type="button" onclick="hideRejectModal()"
                                class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300">Cancel</button>
                            <button type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Reason Modal -->
    <div id="rejectionReasonModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Rejection Reason</h3>
                <div class="mt-2 px-7 py-3">
                    <p id="rejectionReasonText" class="text-sm text-gray-700"></p>
                    <div class="flex justify-end mt-4">
                        <button type="button" onclick="hideRejectionReasonModal()"
                            class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showRejectModal(billId) {
                const modal = document.getElementById('rejectModal');
                const form = document.getElementById('rejectForm');
                form.action = `/bills/${billId}/update-status`;
                modal.classList.remove('hidden');
            }

            function hideRejectModal() {
                const modal = document.getElementById('rejectModal');
                modal.classList.add('hidden');
            }

            function showRejectionReasonModal(reason) {
                const modal = document.getElementById('rejectionReasonModal');
                const text = document.getElementById('rejectionReasonText');
                text.innerText = reason;
                modal.classList.remove('hidden');
            }

            function hideRejectionReasonModal() {
                const modal = document.getElementById('rejectionReasonModal');
                modal.classList.add('hidden');
            }
        </script>
    @endpush

@endsection
