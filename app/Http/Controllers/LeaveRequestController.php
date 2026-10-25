<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $query = LeaveRequest::with(['employee.department', 'employee.position']);

            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }

            if ($request->filled('leave_type')) {
                $query->where('leave_type', $request->input('leave_type'));
            }

            $leaveRequests = $query->latest()->paginate(10)->withQueryString();
        } else {
            $employee = $user->employee;
            $leaveRequests = $employee
                ? $employee->leaveRequests()->latest()->paginate(10)
                : collect();
        }

        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function create(): View|RedirectResponse
    {
        $user = auth()->user();

        if (!$user->isAdmin() && !$user->employee) {
            return redirect()->route('dashboard')->with('error', 'No employee record linked to your user account.');
        }

        $employees = $user->isAdmin()
            ? Employee::where('status', 'active')->orderBy('name')->get()
            : collect([$user->employee]);

        return view('leave-requests.create', compact('employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $rules = [
            'leave_type' => ['required', 'in:annual,sick,unpaid,maternity,other'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:1000'],
        ];

        if ($user->isAdmin()) {
            $rules['employee_id'] = ['required', 'exists:employees,id'];
        }

        $validated = $request->validate($rules);

        $employeeId = $user->isAdmin() ? $validated['employee_id'] : $user->employee?->id;

        if (!$employeeId) {
            return back()->withErrors(['employee' => 'Employee profile not found.']);
        }

        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);
        $totalDays = $start->diffInDays($end) + 1;

        LeaveRequest::create([
            'employee_id' => $employeeId,
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('leave-requests.index')->with('status', 'Leave request submitted successfully.');
    }

    public function approve(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $leaveRequest->update([
            'status' => 'approved',
            'admin_notes' => $request->input('admin_notes', 'Approved by administrator.'),
        ]);

        return redirect()->route('leave-requests.index')->with('status', 'Leave request approved.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $request->validate([
            'admin_notes' => ['required', 'string', 'max:1000'],
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->input('admin_notes'),
        ]);

        return redirect()->route('leave-requests.index')->with('status', 'Leave request rejected.');
    }

    public function destroy(LeaveRequest $leaveRequest): RedirectResponse
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            abort_unless($leaveRequest->employee_id === $user->employee?->id && $leaveRequest->status === 'pending', 403);
        }

        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')->with('status', 'Leave request removed.');
    }
}
