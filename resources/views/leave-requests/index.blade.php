<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Leave Requests') }}
            </h2>
            <a href="{{ route('leave-requests.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                + New Leave Request
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if(Auth::user()->isAdmin())
                <!-- Admin Status Filter -->
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                    <form method="GET" action="{{ route('leave-requests.index') }}" class="flex flex-wrap gap-3 items-center">
                        <div>
                            <select name="status" class="text-sm rounded-lg border-gray-300">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Only</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div>
                            <select name="leave_type" class="text-sm rounded-lg border-gray-300">
                                <option value="">All Leave Types</option>
                                <option value="annual" {{ request('leave_type') === 'annual' ? 'selected' : '' }}>Annual Leave</option>
                                <option value="sick" {{ request('leave_type') === 'sick' ? 'selected' : '' }}>Sick Leave</option>
                                <option value="unpaid" {{ request('leave_type') === 'unpaid' ? 'selected' : '' }}>Unpaid Leave</option>
                                <option value="maternity" {{ request('leave_type') === 'maternity' ? 'selected' : '' }}>Maternity / Paternity</option>
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-semibold hover:bg-slate-700">Filter</button>
                    </form>
                </div>
            @endif

            <!-- Leave Requests Table -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3">Employee</th>
                                <th class="px-6 py-3">Type</th>
                                <th class="px-6 py-3">Dates</th>
                                <th class="px-6 py-3">Days</th>
                                <th class="px-6 py-3">Reason</th>
                                <th class="px-6 py-3">Status & Notes</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($leaveRequests as $leave)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $leave->employee->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $leave->employee->department->name }} &bull; {{ $leave->employee->position->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 capitalize font-medium text-gray-800">
                                        {{ $leave->leave_type }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                        {{ $leave->start_date->format('M d, Y') }}<br>
                                        <span class="text-xs text-gray-400">to {{ $leave->end_date->format('M d, Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-800">
                                        {{ $leave->total_days }} {{ Str::plural('day', $leave->total_days) }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 max-w-xs">
                                        <p class="truncate">{{ $leave->reason }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($leave->status === 'approved')
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700">Approved</span>
                                        @elseif($leave->status === 'rejected')
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-50 text-rose-700">Rejected</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-50 text-amber-700">Pending</span>
                                        @endif

                                        @if($leave->admin_notes)
                                            <p class="text-xs text-gray-400 mt-1 italic">{{ $leave->admin_notes }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        @if(Auth::user()->isAdmin() && $leave->status === 'pending')
                                            <div class="flex justify-end gap-2">
                                                <form method="POST" action="{{ route('leave-requests.approve', $leave) }}">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded text-xs font-semibold hover:bg-emerald-700">Approve</button>
                                                </form>

                                                <div x-data="{ openReject: false }">
                                                    <button @click="openReject = true" type="button" class="px-3 py-1 bg-rose-600 text-white rounded text-xs font-semibold hover:bg-rose-700">Reject</button>
                                                    <div x-show="openReject" class="fixed inset-0 bg-slate-900/50 flex items-center justify-center p-4 z-50">
                                                        <div @click.away="openReject = false" class="bg-white rounded-xl max-w-sm w-full p-5 text-left space-y-3">
                                                            <h4 class="font-bold text-gray-900">Reject Leave Request</h4>
                                                            <p class="text-xs text-gray-500">Provide an explanation note for the employee:</p>
                                                            <form method="POST" action="{{ route('leave-requests.reject', $leave) }}" class="space-y-3">
                                                                @csrf
                                                                <textarea name="admin_notes" required placeholder="Reason for rejection..." class="w-full text-xs rounded-lg border-gray-300" rows="3"></textarea>
                                                                <div class="flex justify-end gap-2">
                                                                    <button type="button" @click="openReject = false" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded text-xs">Cancel</button>
                                                                    <button type="submit" class="px-3 py-1.5 bg-rose-600 text-white rounded text-xs font-semibold hover:bg-rose-700">Confirm Reject</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif(!Auth::user()->isAdmin() && $leave->status === 'pending')
                                            <form method="POST" action="{{ route('leave-requests.destroy', $leave) }}" onsubmit="return confirm('Cancel this leave request?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-500 hover:text-rose-700 font-semibold text-xs">Cancel</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400">Processed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        No leave applications found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($leaveRequests, 'hasPages') && $leaveRequests->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $leaveRequests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
