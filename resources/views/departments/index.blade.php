<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Departments & Positions') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if(Auth::user()->isAdmin())
                <!-- Create Department Form Card -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm" x-data="{ open: false }">
                    <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                        <div class="flex items-center gap-2">
                            <span class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </span>
                            <h3 class="font-bold text-gray-900">Add New Department</h3>
                        </div>
                        <button type="button" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800" x-text="open ? 'Close' : '+ Add Department'"></button>
                    </div>

                    <form x-show="open" method="POST" action="{{ route('departments.store') }}" class="mt-4 pt-4 border-t space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase">Department Name</label>
                                <input type="text" name="name" required placeholder="e.g. Engineering" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase">Department Code</label>
                                <input type="text" name="code" required placeholder="e.g. ENG" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase">Description</label>
                            <textarea name="description" rows="2" placeholder="Brief outline of departmental scope..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">Save Department</button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Departments Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($departments as $department)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $department->name }}</h3>
                                        <span class="px-2 py-0.5 text-xs font-semibold bg-gray-100 text-gray-700 rounded">{{ $department->code }}</span>
                                    </div>
                                    @if($department->description)
                                        <p class="text-sm text-gray-500 mt-1">{{ $department->description }}</p>
                                    @endif
                                </div>
                                @if(Auth::user()->isAdmin())
                                    <form method="POST" action="{{ route('departments.destroy', $department) }}" onsubmit="return confirm('Delete department? Associated positions and staff will be affected.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-semibold">Delete</button>
                                    </form>
                                @endif
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Positions ({{ $department->positions->count() }})</h4>
                                    <span class="text-xs text-gray-500">{{ $department->employees_count }} Assigned Staff</span>
                                </div>

                                <div class="space-y-2">
                                    @forelse($department->positions as $position)
                                        <div class="flex justify-between items-center p-2.5 rounded-lg bg-gray-50 border border-gray-100 text-sm">
                                            <div>
                                                <span class="font-medium text-gray-800">{{ $position->name }}</span>
                                                <div class="text-xs text-emerald-600">Base: ${{ number_format($position->basic_salary, 2) }}/mo</div>
                                            </div>
                                            @if(Auth::user()->isAdmin())
                                                <form method="POST" action="{{ route('positions.destroy', $position) }}" onsubmit="return confirm('Delete this position?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-rose-600 text-xs">&times;</button>
                                                </form>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="text-xs text-gray-400 italic">No job positions defined yet.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        @if(Auth::user()->isAdmin())
                            <!-- Add Position Inline -->
                            <div class="mt-6 pt-4 border-t border-gray-100" x-data="{ openPos: false }">
                                <button type="button" @click="openPos = !openPos" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                    <span x-text="openPos ? 'Cancel' : '+ Add Position to ' + '{{ $department->name }}'"></span>
                                </button>
                                <form x-show="openPos" method="POST" action="{{ route('positions.store') }}" class="mt-3 space-y-3">
                                    @csrf
                                    <input type="hidden" name="department_id" value="{{ $department->id }}">
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="text" name="name" required placeholder="Position Title" class="text-xs rounded-md border-gray-300">
                                        <input type="number" step="0.01" name="basic_salary" required placeholder="Base Salary ($)" class="text-xs rounded-md border-gray-300">
                                    </div>
                                    <button type="submit" class="px-3 py-1.5 bg-slate-800 text-white rounded text-xs font-semibold hover:bg-slate-700">Save Position</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
