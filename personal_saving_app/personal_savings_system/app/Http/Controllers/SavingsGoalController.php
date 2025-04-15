<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class SavingsGoalController extends Controller
{
    public function index()
    {
        $goals = Auth::user()->savingsGoals()->orderBy('deadline')->get();
        return view('savings.index', compact('goals'));
    }

    public function create()
    {
        return view('savings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0',
            'deadline' => 'required|date|after:today',
        ]);

        Auth::user()->savingsGoals()->create([
            'title' => $validated['title'],
            'target_amount' => $validated['target_amount'],
            'deadline' => $validated['deadline'],
            'saved_amount' => 0,
            'is_completed' => false
        ]);

        return redirect()->route('savings.index')
            ->with('success', 'Savings goal created successfully!');
    }

    public function show($id)
    {
        $goal = Auth::user()->savingsGoals()->findOrFail($id);
        return view('savings.show', compact('goal'));
    }

    public function edit($id)
    {
        $goal = Auth::user()->savingsGoals()->findOrFail($id);
        return view('savings.edit', compact('goal'));
    }

    public function update(Request $request, $id)
    {
        $goal = Auth::user()->savingsGoals()->findOrFail($id);

        // If only updating saved_amount
        if ($request->has('saved_amount') && !$request->has('title')) {
            $validated = $request->validate([
                'saved_amount' => ['required', 'numeric', 'min:0', 'max:' . $goal->target_amount],
            ]);

            $goal->update([
                'saved_amount' => $validated['saved_amount'],
                'is_completed' => $validated['saved_amount'] >= $goal->target_amount
            ]);

            return redirect()->route('savings.show', $goal)
                ->with('success', 'Progress updated successfully!');
        }

        // If updating all goal details
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'target_amount' => ['required', 'numeric', 'min:' . $goal->saved_amount],
            'deadline' => 'required|date|after:today',
        ]);

        $goal->update($validated);

        return redirect()->route('savings.show', $goal)
            ->with('success', 'Savings goal updated successfully!');
    }

    public function destroy($id)
    {
        $goal = Auth::user()->savingsGoals()->findOrFail($id);
        $goal->delete();

        return redirect()->route('savings.index')
            ->with('success', 'Savings goal deleted successfully!');
    }
} 