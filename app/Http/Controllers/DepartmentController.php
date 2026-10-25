<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::withCount(['employees', 'positions'])
            ->with('positions')
            ->orderBy('name')
            ->get();

        return view('departments.index', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:departments,code'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('status', 'Department created successfully.');
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:departments,code,' . $department->id],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('status', 'Department updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $department->delete();

        return redirect()->route('departments.index')->with('status', 'Department deleted successfully.');
    }
}
