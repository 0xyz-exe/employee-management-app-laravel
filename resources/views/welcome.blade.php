<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'StaffHub') }} - Human Resource Management</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-slate-50 text-slate-800">
        <div class="min-h-screen flex flex-col justify-between">
            <header class="max-w-7xl w-full mx-auto px-6 py-6 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-900 tracking-tight">StaffHub HR</span>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-indigo-600 transition">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">Get Started</a>
                        @endif
                    @endauth
                </div>
            </header>

            <main class="max-w-7xl mx-auto px-6 py-12 flex-1 flex flex-col justify-center">
                <div class="text-center max-w-3xl mx-auto space-y-6">
                    <span class="px-3 py-1 bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-full text-xs font-semibold uppercase tracking-wider">Enterprise HR & People Operations</span>
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Modern Employee Management & Leave Tracking
                    </h1>
                    <p class="text-lg text-slate-600 leading-relaxed">
                        Centralize organizational departments, job positions, employee records, compensation details, and automate employee leave request workflows seamlessly.
                    </p>
                    <div class="flex flex-wrap justify-center gap-4 pt-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">Open Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">Access System Demo</a>
                        @endauth
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Departments & Positions</h3>
                        <p class="text-sm text-slate-600">Structured organizational hierarchy with custom job titles, salary base benchmarks, and division assignments.</p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Employee Directory</h3>
                        <p class="text-sm text-slate-600">Complete employee records with contact information, hiring dates, compensation, and active/inactive status tracking.</p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Leave Workflow</h3>
                        <p class="text-sm text-slate-600">Employee leave applications for annual, sick, or personal time-off with administrative review, approval, and rejection notes.</p>
                    </div>
                </div>
            </main>

            <footer class="max-w-7xl w-full mx-auto px-6 py-6 border-t border-slate-200 text-center text-xs text-slate-500">
                StaffHub &bull; Laravel 13 &bull; SQLite &bull; Breeze &bull; Tailwind CSS
            </footer>
        </div>
    </body>
</html>
