<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Employee') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <form method="POST" action="{{ route('employees.store') }}" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                        <div class="p-4 bg-rose-50 text-rose-800 rounded-lg text-sm">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase">Employee Code *</label>
                            <input type="text" name="employee_code" value="{{ old('employee_code') }}" required placeholder="e.g. EMP-010" class="mt-1 w-full text-sm rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Jane Doe" class="mt-1 w-full text-sm rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="jane@example.com" class="mt-1 w-full text-sm rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+1 555-0199" class="mt-1 w-full text-sm rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase">Department *</label>
                            <select name="department_id" id="department_id" required class="mt-1 w-full text-sm rounded-lg border-gray-300">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase">Position *</label>
                            <select name="position_id" id="position_id" required class="mt-1 w-full text-sm rounded-lg border-gray-300">
                                <option value="">Select Position</option>
                                @foreach($departments as $dept)
                                    @foreach($dept->positions as $pos)
                                        <option value="{{ $pos->id }}" data-dept="{{ $dept->id }}" {{ old('position_id') == $pos->id ? 'selected' : '' }}>
                                            {{ $dept->name }} - {{ $pos->name }} (${{ number_format($pos->basic_salary, 2) }})
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase">Monthly Salary ($) *</label>
                            <input type="number" step="0.01" name="salary" value="{{ old('salary', 5000) }}" required class="mt-1 w-full text-sm rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase">Hire Date *</label>
                            <input type="date" name="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}" required class="mt-1 w-full text-sm rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase">Status *</label>
                            <select name="status" required class="mt-1 w-full text-sm rounded-lg border-gray-300">
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase">Link User Account (Optional)</label>
                            <select name="user_id" class="mt-1 w-full text-sm rounded-lg border-gray-300">
                                <option value="">None / Standalone record</option>
                                @foreach($usersWithoutEmployee as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase">Residential Address</label>
                        <textarea name="address" rows="2" placeholder="Full residential street address..." class="mt-1 w-full text-sm rounded-lg border-gray-300">{{ old('address') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('employees.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700">Save Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
