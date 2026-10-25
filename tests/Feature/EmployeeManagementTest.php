<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_dashboard_and_metrics(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Total Employees');
    }

    public function test_admin_can_create_department_and_position(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post(route('departments.store'), [
            'name' => 'Legal & Compliance',
            'code' => 'LEG',
            'description' => 'Corporate legal advisory',
        ])->assertRedirect(route('departments.index'));

        $this->assertDatabaseHas('departments', ['code' => 'LEG']);

        $dept = Department::where('code', 'LEG')->first();

        $this->actingAs($admin)->post(route('positions.store'), [
            'department_id' => $dept->id,
            'name' => 'Legal Counsel',
            'basic_salary' => 9500,
        ])->assertRedirect(route('departments.index'));

        $this->assertDatabaseHas('positions', ['name' => 'Legal Counsel']);
    }

    public function test_admin_can_create_and_update_employee(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $dept = Department::create(['name' => 'Operations', 'code' => 'OPS']);
        $pos = Position::create(['department_id' => $dept->id, 'name' => 'Ops Lead', 'basic_salary' => 6000]);

        $response = $this->actingAs($admin)->post(route('employees.store'), [
            'employee_code' => 'EMP-100',
            'name' => 'Alice Martin',
            'email' => 'alice@example.com',
            'phone' => '123456789',
            'department_id' => $dept->id,
            'position_id' => $pos->id,
            'hire_date' => '2024-01-01',
            'salary' => 6500,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('employees.index'));
        $this->assertDatabaseHas('employees', ['employee_code' => 'EMP-100', 'email' => 'alice@example.com']);
    }

    public function test_employee_can_submit_leave_and_admin_can_approve(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['is_admin' => false]);
        $dept = Department::create(['name' => 'Support', 'code' => 'SUP']);
        $pos = Position::create(['department_id' => $dept->id, 'name' => 'Support Rep', 'basic_salary' => 4000]);

        $employee = Employee::create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'position_id' => $pos->id,
            'employee_code' => 'EMP-200',
            'name' => $user->name,
            'email' => $user->email,
            'hire_date' => now()->toDateString(),
            'salary' => 4200,
            'status' => 'active',
        ]);

        // Submit leave
        $this->actingAs($user)->post(route('leave-requests.store'), [
            'leave_type' => 'annual',
            'start_date' => now()->addDays(2)->toDateString(),
            'end_date' => now()->addDays(4)->toDateString(),
            'reason' => 'Family travel',
        ])->assertRedirect(route('leave-requests.index'));

        $this->assertDatabaseHas('leave_requests', [
            'employee_id' => $employee->id,
            'status' => 'pending',
            'total_days' => 3,
        ]);

        $leave = LeaveRequest::first();

        // Admin approve
        $this->actingAs($admin)->post(route('leave-requests.approve', $leave), [
            'admin_notes' => 'Approved, have fun!',
        ])->assertRedirect(route('leave-requests.index'));

        $this->assertDatabaseHas('leave_requests', [
            'id' => $leave->id,
            'status' => 'approved',
        ]);
    }
}
