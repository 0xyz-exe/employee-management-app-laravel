<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Employee::with(['department', 'position', 'user']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $employees = $query->orderBy('name')->paginate(10)->withQueryString();
        $departments = Department::orderBy('name')->get();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $departments = Department::with('positions')->orderBy('name')->get();
        $usersWithoutEmployee = User::whereDoesntHave('employee')->orderBy('name')->get();

        return view('employees.create', compact('departments', 'usersWithoutEmployee'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'employee_code' => ['required', 'string', 'max:50', 'unique:employees,employee_code'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:employees,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'hire_date' => ['required', 'date'],
            'salary' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'address' => ['nullable', 'string', 'max:500'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')->with('status', 'Employee created successfully.');
    }

    public function show(Employee $employee): View
    {
        $employee->load(['department', 'position', 'user', 'leaveRequests' => function ($q) {
            $q->latest();
        }]);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $departments = Department::with('positions')->orderBy('name')->get();
        $usersWithoutEmployee = User::where(function ($q) use ($employee) {
            $q->whereDoesntHave('employee')
              ->orWhere('id', $employee->user_id);
        })->orderBy('name')->get();

        return view('employees.edit', compact('employee', 'departments', 'usersWithoutEmployee'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'employee_code' => ['required', 'string', 'max:50', 'unique:employees,employee_code,' . $employee->id],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:employees,email,' . $employee->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'hire_date' => ['required', 'date'],
            'salary' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'address' => ['nullable', 'string', 'max:500'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        $employee->update($validated);

        return redirect()->route('employees.show', $employee)->with('status', 'Employee details updated.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $employee->delete();

        return redirect()->route('employees.index')->with('status', 'Employee deleted.');
    }
}
