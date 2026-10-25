<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('status', 'active')->count(),
            'total_departments' => Department::count(),
            'pending_leaves' => LeaveRequest::where('status', 'pending')->count(),
        ];

        $recentLeaves = LeaveRequest::with(['employee.department'])
            ->latest()
            ->take(5)
            ->get();

        $departments = Department::withCount('employees')
            ->orderByDesc('employees_count')
            ->take(5)
            ->get();

        $myEmployee = $user->employee;
        $myLeaves = $myEmployee
            ? $myEmployee->leaveRequests()->latest()->take(5)->get()
            : collect();

        return view('dashboard', compact('stats', 'recentLeaves', 'departments', 'myEmployee', 'myLeaves'));
    }
}
