<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Apply for Leave') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <form method="POST" action="{{ route('leave-requests.store') }}" class="space-y-6">
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

                    @if(Auth::user()->isAdmin())
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase">Employee *</label>
                            <select name="employee_id" required class="mt-1 w-full text-sm rounded-lg border-gray-300">
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->department->name }} - {{ $emp->position->name }})</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="p-4 bg-indigo-50/50 rounded-lg border border-indigo-100 text-sm">
                            <span class="text-xs text-indigo-700 font-semibold uppercase">Applicant Profile</span>
                            <p class="font-bold text-gray-900 mt-1">{{ Auth::user()->employee->name }}</p>
                            <p class="text-xs text-gray-600">{{ Auth::user()->employee->department->name }} &bull; {{ Auth::user()->employee->position->name }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase">Leave Category *</label>
                            <select name="leave_type" required class="mt-1 w-full text-sm rounded-lg border-gray-300">
                                <option value="annual" {{ old('leave_type') === 'annual' ? 'selected' : '' }}>Annual / Vacation Leave</option>
                                <option value="sick" {{ old('leave_type') === 'sick' ? 'selected' : '' }}>Sick Leave</option>
                                <option value="unpaid" {{ old('leave_type') === 'unpaid' ? 'selected' : '' }}>Unpaid Leave</option>
                                <option value="maternity" {{ old('leave_type') === 'maternity' ? 'selected' : '' }}>Maternity / Paternity Leave</option>
                                <option value="other" {{ old('leave_type') === 'other' ? 'selected' : '' }}>Other Personal Leave</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase">Start Date *</label>
                            <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required class="mt-1 w-full text-sm rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase">End Date *</label>
                            <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required class="mt-1 w-full text-sm rounded-lg border-gray-300">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase">Reason / Notes *</label>
                        <textarea name="reason" rows="3" required placeholder="State reason for absence and handoff coverage details..." class="mt-1 w-full text-sm rounded-lg border-gray-300">{{ old('reason') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('leave-requests.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700">Submit Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
