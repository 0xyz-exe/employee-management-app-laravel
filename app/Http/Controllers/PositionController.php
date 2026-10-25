<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:255'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
        ]);

        Position::create($validated);

        return redirect()->route('departments.index')->with('status', 'Position added successfully.');
    }

    public function update(Request $request, Position $position): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
        ]);

        $position->update($validated);

        return redirect()->route('departments.index')->with('status', 'Position updated successfully.');
    }

    public function destroy(Position $position): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $position->delete();

        return redirect()->route('departments.index')->with('status', 'Position deleted successfully.');
    }
}
