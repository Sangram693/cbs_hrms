@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Leaves</h2>
        <div class="mb-4 flex flex-row justify-between items-center">
            <a href="{{ route('leaves.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded inline-block">Add Leave</a>
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isHr())
            <a href="{{ route('leavetypes.index', auth()->user()->isSuperAdmin() ? ['company_id'=>request('company_id')] : []) }}" class="bg-green-500 text-white px-3 py-1 rounded inline-block ml-4">
                <i class="fas fa-list mr-1"></i> Manage Leave Types
            </a>
            @endif
        </div>
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="border-b border-gray-300 bg-gray-100">
                    <th class="py-2 px-4 text-center border-r border-gray-300">Employee</th>
                    <th class="py-2 px-4 text-center border-r border-gray-300">Type</th>
                    <th class="py-2 px-4 text-center border-r border-gray-300">From</th>
                    <th class="py-2 px-4 text-center border-r border-gray-300">To</th>
                    <th class="py-2 px-4 text-center border-r border-gray-300">Status</th>
                    @if (auth()->user()->isSuperAdmin())
                        <th class="py-2 px-4 text-center border-r border-gray-300">Company</th>
                    @endif
                    <th class="py-2 px-4 text-center border-r border-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $leave)
                    <tr class="border-b border-gray-300">
                        <td class="py-2 px-4 text-center border-r border-gray-300">
                            @if($leave->employee)
                                <div>{{ $leave->employee->name }}</div>
                                @if($leave->employee->emp_id)
                                    <div class="text-xs text-gray-500">({{ $leave->employee->emp_id }})</div>
                                @endif
                            @else
                                No employee found
                            @endif
                        </td>
                        <td class="py-2 px-4 text-center border-r border-gray-300">{{ $leave->leave_type }}</td>
                        <td class="py-2 px-4 text-center border-r border-gray-300">{{ $leave->start_date }}</td>
                        <td class="py-2 px-4 text-center border-r border-gray-300">{{ $leave->end_date }}</td>
                        <td class="py-2 px-4 text-center border-r border-gray-300">
                            @php
                                $statusColor =
                                    [
                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                        'Approved' => 'bg-green-100 text-green-800',
                                        'Rejected' => 'bg-red-100 text-red-800',
                                    ][$leave->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-3 py-1 rounded font-semibold pending-status {{ $statusColor }}"
                                data-leave-id="{{ $leave->id }}"
                                style="cursor:pointer; {{ $leave->status === 'Pending' ? 'text-decoration:underline;' : 'text-decoration:none;' }}">
                                {{ $leave->status }}
                            </span>
                        </td>
                        @if (auth()->user()->isSuperAdmin())
                            <td class="py-2 px-4 text-center border-r border-gray-300">
                                {{ $leave->employee && $leave->employee->company ? $leave->employee->company->name : '-' }}
                            </td>
                        @endif
                        <td class="py-2 px-4 text-center border-r border-gray-300">
                            @if(
                                auth()->user()->isSuperAdmin() ||
                                auth()->user()->isAdmin() ||
                                auth()->user()->isHr() ||
                                (auth()->user()->isUser() && $leave->status === 'Pending' && $leave->employee && $leave->employee->id === auth()->user()->employee->id)
                            )
                                <a href="{{ route('leaves.edit', $leave->id) }}" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            @endif
                            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isHr())
                            <form action="{{ route('leaves.destroy', $leave->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this leave?');" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 bg-transparent border-none p-0 m-0 cursor-pointer" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-2 px-4 text-center border-t border-gray-300">No leave records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal HTML -->
    @php
        $user = auth()->user();
        $canChangeStatus = $user->isSuperAdmin() || $user->isAdmin() || $user->isHr();
    @endphp
    @if($canChangeStatus)
    <div id="leave-modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded shadow-lg p-6 w-full max-w-md relative">
            <button id="leave-modal-close" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
            <h3 class="text-lg font-bold mb-4">Leave Details</h3>
            <div class="mb-2 flex items-top gap-2 min-w-0 justify-start">
                <strong class="shrink-0">Employee:</strong>
                <span id="leave-modal-employee" class="flex-1 min-w-0 truncate"></span>
            </div>
            <div class="mb-2"><strong>Type:</strong> <span id="leave-modal-type"></span></div>
            <div class="mb-2"><strong>From:</strong> <span id="leave-modal-from"></span></div>
            <div class="mb-2"><strong>To:</strong> <span id="leave-modal-to"></span></div>
            <div class="mb-2"><strong>Reason:</strong> <span id="leave-modal-reason"></span></div>
            <form method="POST" id="leave-modal-action-form" class="mt-4 flex gap-2">
                @csrf
                <input type="hidden" name="leave_id" id="leave-modal-id">
                <button type="submit" id="leave-modal-approve"
                    class="bg-green-500 text-white px-4 py-2 rounded">Approve</button>
                <button type="submit" id="leave-modal-reject"
                    class="bg-red-500 text-white px-4 py-2 rounded">Reject</button>
            </form>
        </div>
    </div>
    @endif
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.pending-status').forEach(function(el) {
                // Only attach click event if status is Pending
                if (el.textContent.trim() === 'Pending') {
                    el.style.cursor = 'pointer';
                    el.addEventListener('click', function() {
                        const leaveId = this.getAttribute('data-leave-id');
                        fetch(`/leaves/${leaveId}`)
                            .then(response => response.ok ? response.json() : Promise.reject('Not found'))
                            .then(data => {
                                let empDisplay = '';
                                if (data.employee && (data.employee.name || data.employee.emp_id)) {
                                    empDisplay = `<div>${data.employee.name ?? ''}</div>`;
                                    if (data.employee.emp_id) {
                                        empDisplay += `<div class='text-xs text-gray-500'>(${data.employee.emp_id})</div>`;
                                    }
                                } else if (data.employee_id && data.employee_name) {
                                    empDisplay = `<div>${data.employee_name}</div>`;
                                    if (data.employee_emp_id) {
                                        empDisplay += `<div class='text-xs text-gray-500'>(${data.employee_emp_id})</div>`;
                                    }
                                } else if (data.employee_id) {
                                    // Try to show employee name and emp_id from the table row if available
                                    const row = document.querySelector(
                                        `[data-leave-id='${leaveId}']`).closest('tr');
                                    if (row) {
                                        const empCell = row.querySelector('td');
                                        empDisplay = empCell ? empCell.innerHTML.trim() : data.employee_id;
                                    } else {
                                        empDisplay = data.employee_id;
                                    }
                                } else {
                                    empDisplay = '-';
                                }
                                document.getElementById('leave-modal-employee').innerHTML = empDisplay;
                                document.getElementById('leave-modal-type').innerText = data.leave_type;
                                document.getElementById('leave-modal-from').innerText = data.start_date;
                                document.getElementById('leave-modal-to').innerText = data.end_date;
                                document.getElementById('leave-modal-reason').innerText = data.reason;
                                document.getElementById('leave-modal-id').value = leaveId;
                                // Set form action to the new changeStatus route
                                const form = document.getElementById('leave-modal-action-form');
                                form.setAttribute('action', `/leaves/${leaveId}/change-status`);
                                // Set which button was clicked for status
                                document.getElementById('leave-modal-approve').onclick = function(e) {
                                    form.statusAction = 'Approved';
                                };
                                document.getElementById('leave-modal-reject').onclick = function(e) {
                                    form.statusAction = 'Rejected';
                                };
                                document.getElementById('leave-modal').classList.remove('hidden');
                            })
                            .catch(() => {
                                alert('Could not load leave details.');
                            });
                    });
                } else {
                    el.style.cursor = 'default';
                    el.removeAttribute('data-leave-id');
                }
            });
            // Intercept form submit to add status
            document.getElementById('leave-modal-action-form').onsubmit = function(e) {
                if (!this.statusAction) {
                    e.preventDefault();
                    alert('Please select Approve or Reject.');
                    return false;
                }
                // Add a hidden input for status
                let statusInput = this.querySelector('input[name="status"]');
                if (!statusInput) {
                    statusInput = document.createElement('input');
                    statusInput.type = 'hidden';
                    statusInput.name = 'status';
                    this.appendChild(statusInput);
                }
                statusInput.value = this.statusAction;
                return true;
            };
            document.getElementById('leave-modal-close').onclick = function() {
                document.getElementById('leave-modal').classList.add('hidden');
            };
            document.getElementById('leave-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
