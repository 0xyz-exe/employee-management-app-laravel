<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $employee->name }}
            </h2>
            <div class="flex gap-2">
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('employees.edit', $employee) }}" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-semibold hover:bg-slate-700 transition">
                        Edit Profile
                    </a>
                @endif
                <a href="{{ route('employees.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                    &larr; Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Personal Summary -->
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
                    <div class="text-center pb-4 border-b">
                        <div class="w-20 h-20 mx-auto rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-2xl mb-3">
                            {{ substr($employee->name, 0, 2) }}
                        </div>
                        <h3 class="font-bold text-lg text-gray-900">{{ $employee->name }}</h3>
                        <p class="text-xs font-mono text-gray-500">{{ $employee->employee_code }}</p>
                        <div class="mt-2">
                            @if($employee->status === 'active')
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700">Active</span>
                            @else
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-xs text-gray-400 uppercase">Email</span>
                            <p class="text-gray-800 font-medium">{{ $employee->email }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 uppercase">Phone</span>
                            <p class="text-gray-800 font-medium">{{ $employee->phone ?: 'Not provided' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 uppercase">Residential Address</span>
                            <p class="text-gray-800 font-medium">{{ $employee->address ?: 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Employment & Payroll Details -->
                <div class="md:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-6">
                    <h3 class="font-bold text-gray-900 text-base">Employment & Compensation Details</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="text-xs text-gray-500 uppercase">Department</span>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $employee->department->name }}</p>
                            <p class="text-xs text-gray-500">Code: {{ $employee->department->code }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="text-xs text-gray-500 uppercase">Position</span>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $employee->position->name }}</p>
                            <p class="text-xs text-gray-500">Base salary benchmark: ${{ number_format($employee->position->basic_salary, 2) }}</p>
                        </div>
                        <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                            <span class="text-xs text-emerald-700 uppercase font-semibold">Current Salary</span>
                            <p class="text-2xl font-extrabold text-emerald-800 mt-1">${{ number_format($employee->salary, 2) }}</p>
                            <p class="text-xs text-emerald-600">Per month</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="text-xs text-gray-500 uppercase">Hiring Date</span>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $employee->hire_date->format('F d, Y') }}</p>
                            <p class="text-xs text-gray-500">Tenure: {{ $employee->hire_date->diffForHumans(null, true) }}</p>
                        </div>
                    </div>

                    <!-- Leave History for this Employee -->
                    <div class="pt-4 border-t">
                        <h4 class="font-bold text-sm text-gray-900 mb-3">Leave History ({{ $employee->leaveRequests->count() }})</h4>
                        @if($employee->leaveRequests->isEmpty())
                            <p class="text-xs text-gray-500 italic">No leave applications recorded for this employee.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs text-left">
                                    <thead class="text-gray-400 uppercase bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2">Type</th>
                                            <th class="px-3 py-2">Dates</th>
                                            <th class="px-3 py-2">Days</th>
                                            <th class="px-3 py-2">Reason</th>
                                            <th class="px-3 py-2">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($employee->leaveRequests as $req)
                                            <tr>
                                                <td class="px-3 py-2 font-medium capitalize">{{ $req->leave_type }}</td>
                                                <td class="px-3 py-2">{{ $req->start_date->format('M d') }} - {{ $req->end_date->format('M d, Y') }}</td>
                                                <td class="px-3 py-2">{{ $req->total_days }}</td>
                                                <td class="px-3 py-2 text-gray-600 truncate max-w-xs">{{ $req->reason }}</td>
                                                <td class="px-3 py-2">
                                                    @if($req->status === 'approved')
                                                        <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-semibold">Approved</span>
                                                    @elseif($req->status === 'rejected')
                                                        <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 font-semibold">Rejected</span>
                                                    @else
                                                        <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-semibold">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
