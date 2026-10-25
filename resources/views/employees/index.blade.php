<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employee Directory') }}
            </h2>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('employees.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                    + Add Employee
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Search & Filters -->
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <form method="GET" action="{{ route('employees.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div class="sm:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, code, or email..." class="w-full text-sm rounded-lg border-gray-300">
                    </div>
                    <div>
                        <select name="department_id" class="w-full text-sm rounded-lg border-gray-300">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <select name="status" class="w-full text-sm rounded-lg border-gray-300">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-semibold hover:bg-slate-700">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Employee Table -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3">Employee</th>
                                <th class="px-6 py-3">Code</th>
                                <th class="px-6 py-3">Department & Position</th>
                                <th class="px-6 py-3">Compensation</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($employees as $employee)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $employee->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $employee->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-gray-600">
                                        {{ $employee->employee_code }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-800">{{ $employee->position->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $employee->department->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-emerald-700">
                                        ${{ number_format($employee->salary, 2) }}/mo
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($employee->status === 'active')
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700">Active</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('employees.show', $employee) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs">View</a>
                                        @if(Auth::user()->isAdmin())
                                            <a href="{{ route('employees.edit', $employee) }}" class="text-slate-600 hover:text-slate-900 font-semibold text-xs">Edit</a>
                                            <form method="POST" action="{{ route('employees.destroy', $employee) }}" class="inline" onsubmit="return confirm('Delete employee record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-500 hover:text-rose-700 font-semibold text-xs">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        No employee records found matching criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($employees->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $employees->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
