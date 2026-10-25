<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('HR Dashboard') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('leave-requests.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                    + Apply for Leave
                </a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('employees.create') }}" class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm font-semibold hover:bg-slate-800 transition">
                        + New Employee
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Employees</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-2">{{ $stats['total_employees'] }}</p>
                    <p class="text-xs text-emerald-600 font-medium mt-1">{{ $stats['active_employees'] }} Active status</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Departments</p>
                    <p class="text-3xl font-extrabold text-indigo-600 mt-2">{{ $stats['total_departments'] }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-1">Operating business units</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pending Leaves</p>
                    <p class="text-3xl font-extrabold text-amber-500 mt-2">{{ $stats['pending_leaves'] }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-1">Awaiting review</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">My Role</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ Auth::user()->isAdmin() ? 'Administrator' : 'Staff Employee' }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-1">{{ $myEmployee ? $myEmployee->position->name : 'System User' }}</p>
                </div>
            </div>

            <!-- Main Content Split -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Leaves / Activity -->
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base font-bold text-gray-900">
                            {{ Auth::user()->isAdmin() ? 'Recent Leave Applications' : 'My Recent Leave Requests' }}
                        </h3>
                        <a href="{{ route('leave-requests.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                            View All &rarr;
                        </a>
                    </div>

                    @php
                        $leavesToShow = Auth::user()->isAdmin() ? $recentLeaves : $myLeaves;
                    @endphp

                    @if($leavesToShow->isEmpty())
                        <div class="py-8 text-center text-sm text-gray-500">
                            No leave applications recorded yet.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b">
                                    <tr>
                                        <th class="px-4 py-3">Employee</th>
                                        <th class="px-4 py-3">Type</th>
                                        <th class="px-4 py-3">Duration</th>
                                        <th class="px-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($leavesToShow as $leave)
                                        <tr>
                                            <td class="px-4 py-3 font-medium text-gray-900">
                                                {{ $leave->employee->name }}
                                                <div class="text-xs text-gray-400">{{ $leave->employee->department->name }}</div>
                                            </td>
                                            <td class="px-4 py-3 capitalize text-gray-600">{{ $leave->leave_type }}</td>
                                            <td class="px-4 py-3 text-gray-600">
                                                {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                                                <span class="text-xs text-gray-400">({{ $leave->total_days }}d)</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($leave->status === 'approved')
                                                    <span class="px-2 py-0.5 text-xs font-semibold rounded bg-emerald-50 text-emerald-700">Approved</span>
                                                @elseif($leave->status === 'rejected')
                                                    <span class="px-2 py-0.5 text-xs font-semibold rounded bg-rose-50 text-rose-700">Rejected</span>
                                                @else
                                                    <span class="px-2 py-0.5 text-xs font-semibold rounded bg-amber-50 text-amber-700">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Departments Overview -->
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-base font-bold text-gray-900">Departments</h3>
                        <a href="{{ route('departments.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                            Manage &rarr;
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($departments as $dept)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 border border-gray-100">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">{{ $dept->name }}</p>
                                    <p class="text-xs text-gray-500">Code: {{ $dept->code }}</p>
                                </div>
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-indigo-50 text-indigo-700">
                                    {{ $dept->employees_count }} staff
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
