# Employee Management App

Repository: https://github.com/0xyz-exe/employee-management-app-laravel

A modern Human Resource (HR) and workforce management platform built with Laravel 13, Blade, Tailwind CSS, and SQLite.

## Features

- ✅ **Authentication & Roles**: Laravel Breeze authentication with Administrator and Staff role segregation.
- ✅ **Departments & Positions**: Organizational hierarchy with custom departments, job positions, and base salary scales.
- ✅ **Employee Directory**: Complete employee profiles with codes, contact info, department assignments, hiring dates, and salaries.
- ✅ **Leave Management**: Leave requests (Annual, Sick, Unpaid, Maternity/Paternity) with automatic day duration calculations.
- ✅ **Approval Workflow**: Administrative review to approve or reject leave requests with feedback notes.
- ✅ **HR Analytics Dashboard**: High-level overview of total employees, active headcount, department counts, and pending leave applications.

## Tech Stack

Laravel 13.38.0 · Breeze 2.4 · SQLite · Tailwind CSS

## Installation

```bash
git clone https://github.com/0xyz-exe/employee-management-app-laravel.git
cd employee-management-app-laravel
composer install && npm install && npm run build
cp .env.example .env && php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

### Demo Accounts

All accounts use the password: `password`

- **Admin**: `admin@example.com` (Manage departments, positions, employees, approve/reject leaves)
- **Staff User**: `test@example.com` (Sarah Jenkins - view directory, submit leave applications, check status)

## Database Schema

- **users**: `id`, `name`, `email`, `password`, `is_admin`, `timestamps`
- **departments**: `id`, `name`, `code`, `description`, `timestamps`
- **positions**: `id`, `department_id`, `name`, `basic_salary`, `timestamps`
- **employees**: `id`, `user_id`, `department_id`, `position_id`, `employee_code`, `name`, `email`, `phone`, `address`, `hire_date`, `salary`, `status`, `timestamps`
- **leave_requests**: `id`, `employee_id`, `leave_type`, `start_date`, `end_date`, `total_days`, `reason`, `status`, `admin_notes`, `timestamps`

## License

MIT
