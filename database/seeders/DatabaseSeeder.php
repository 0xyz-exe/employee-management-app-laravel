<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin & Test User Accounts
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $testUser = User::create([
            'name' => 'Sarah Jenkins',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // 2. Departments
        $eng = Department::create([
            'name' => 'Engineering',
            'code' => 'ENG',
            'description' => 'Core product engineering, infrastructure, and platform development.',
        ]);

        $hr = Department::create([
            'name' => 'Human Resources',
            'code' => 'HR',
            'description' => 'People operations, talent acquisition, and workforce culture.',
        ]);

        $mkt = Department::create([
            'name' => 'Marketing',
            'code' => 'MKT',
            'description' => 'Growth acquisition, brand marketing, and content strategy.',
        ]);

        $fin = Department::create([
            'name' => 'Finance',
            'code' => 'FIN',
            'description' => 'Financial planning, payroll, and corporate accounting.',
        ]);

        // 3. Positions
        $posSrDev = Position::create(['department_id' => $eng->id, 'name' => 'Senior Software Engineer', 'basic_salary' => 8500]);
        $posFrontDev = Position::create(['department_id' => $eng->id, 'name' => 'Frontend Developer', 'basic_salary' => 6500]);
        $posDevOps = Position::create(['department_id' => $eng->id, 'name' => 'DevOps Engineer', 'basic_salary' => 8000]);

        $posHrMgr = Position::create(['department_id' => $hr->id, 'name' => 'HR Manager', 'basic_salary' => 7000]);
        $posTalent = Position::create(['department_id' => $hr->id, 'name' => 'Talent Acquisition Specialist', 'basic_salary' => 5000]);

        $posMktLead = Position::create(['department_id' => $mkt->id, 'name' => 'Growth Marketing Lead', 'basic_salary' => 6800]);
        $posContent = Position::create(['department_id' => $mkt->id, 'name' => 'Content Strategist', 'basic_salary' => 4800]);

        $posFinanceCtrl = Position::create(['department_id' => $fin->id, 'name' => 'Financial Controller', 'basic_salary' => 7500]);
        $posAccountant = Position::create(['department_id' => $fin->id, 'name' => 'Senior Accountant', 'basic_salary' => 5500]);

        // 4. Employees
        $empSarah = Employee::create([
            'user_id' => $testUser->id,
            'department_id' => $eng->id,
            'position_id' => $posFrontDev->id,
            'employee_code' => 'EMP-001',
            'name' => 'Sarah Jenkins',
            'email' => 'test@example.com',
            'phone' => '+1 (555) 234-5678',
            'address' => '742 Evergreen Terrace, Springfield, OR',
            'hire_date' => now()->subYears(2)->toDateString(),
            'salary' => 6800.00,
            'status' => 'active',
        ]);

        $empDavid = Employee::create([
            'department_id' => $eng->id,
            'position_id' => $posSrDev->id,
            'employee_code' => 'EMP-002',
            'name' => 'David Miller',
            'email' => 'david.miller@example.com',
            'phone' => '+1 (555) 345-6789',
            'address' => '128 Pine St, Seattle, WA',
            'hire_date' => now()->subYears(3)->toDateString(),
            'salary' => 9000.00,
            'status' => 'active',
        ]);

        $empElena = Employee::create([
            'department_id' => $hr->id,
            'position_id' => $posHrMgr->id,
            'employee_code' => 'EMP-003',
            'name' => 'Elena Rostova',
            'email' => 'elena.r@example.com',
            'phone' => '+1 (555) 456-7890',
            'address' => '450 Silicon Way, San Jose, CA',
            'hire_date' => now()->subYears(4)->toDateString(),
            'salary' => 7200.00,
            'status' => 'active',
        ]);

        $empMichael = Employee::create([
            'department_id' => $mkt->id,
            'position_id' => $posMktLead->id,
            'employee_code' => 'EMP-004',
            'name' => 'Michael Chang',
            'email' => 'michael.c@example.com',
            'phone' => '+1 (555) 567-8901',
            'address' => '88 Market St, Austin, TX',
            'hire_date' => now()->subYear()->toDateString(),
            'salary' => 7000.00,
            'status' => 'active',
        ]);

        $empJessica = Employee::create([
            'department_id' => $fin->id,
            'position_id' => $posAccountant->id,
            'employee_code' => 'EMP-005',
            'name' => 'Jessica Alba',
            'email' => 'jessica.a@example.com',
            'phone' => '+1 (555) 678-9012',
            'address' => '100 Wall Street, New York, NY',
            'hire_date' => now()->subYears(2)->toDateString(),
            'salary' => 5800.00,
            'status' => 'active',
        ]);

        $empTom = Employee::create([
            'department_id' => $mkt->id,
            'position_id' => $posContent->id,
            'employee_code' => 'EMP-006',
            'name' => 'Tom Vance',
            'email' => 'tom.vance@example.com',
            'phone' => '+1 (555) 789-0123',
            'address' => '300 Boulder Ave, Denver, CO',
            'hire_date' => now()->subMonths(8)->toDateString(),
            'salary' => 4800.00,
            'status' => 'inactive',
        ]);

        // 5. Leave Requests
        LeaveRequest::create([
            'employee_id' => $empSarah->id,
            'leave_type' => 'annual',
            'start_date' => now()->addDays(10)->toDateString(),
            'end_date' => now()->addDays(14)->toDateString(),
            'total_days' => 5,
            'reason' => 'Annual family vacation travel booked months in advance.',
            'status' => 'pending',
        ]);

        LeaveRequest::create([
            'employee_id' => $empSarah->id,
            'leave_type' => 'sick',
            'start_date' => now()->subDays(20)->toDateString(),
            'end_date' => now()->subDays(19)->toDateString(),
            'total_days' => 2,
            'reason' => 'Viral seasonal flu and medical rest advised by physician.',
            'status' => 'approved',
            'admin_notes' => 'Medical certificate verified by HR.',
        ]);

        LeaveRequest::create([
            'employee_id' => $empDavid->id,
            'leave_type' => 'annual',
            'start_date' => now()->addDays(5)->toDateString(),
            'end_date' => now()->addDays(7)->toDateString(),
            'total_days' => 3,
            'reason' => 'Attending developer summit conference and taking adjacent rest day.',
            'status' => 'approved',
            'admin_notes' => 'Approved - project handoff completed to junior team.',
        ]);

        LeaveRequest::create([
            'employee_id' => $empMichael->id,
            'leave_type' => 'unpaid',
            'start_date' => now()->addDays(15)->toDateString(),
            'end_date' => now()->addDays(16)->toDateString(),
            'total_days' => 2,
            'reason' => 'Moving personal residence and handling apartment logistics.',
            'status' => 'pending',
        ]);
    }
}
