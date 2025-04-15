<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\SavingsGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function dashboard()
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'total_transactions' => Transaction::count(),
                'total_savings_goals' => SavingsGoal::count(),
                'total_savings' => Transaction::where('type', 'saving')->sum('amount'),
            ];

            $recent_users = User::latest()->take(5)->get();
            $recent_transactions = Transaction::with('user')
                ->latest('date')
                ->take(10)
                ->get();
            $active_goals = SavingsGoal::with('user')
                ->where('is_completed', false)
                ->latest()
                ->take(5)
                ->get();

            return view('admin.dashboard', compact('stats', 'recent_users', 'recent_transactions', 'active_goals'));
        } catch (\Exception $e) {
            Log::error('Admin dashboard error: ' . $e->getMessage());
            return back()->with('error', 'Error loading admin dashboard data.');
        }
    }

    public function users()
    {
        $users = User::withCount(['transactions', 'savingsGoals'])->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function transactions()
    {
        $transactions = Transaction::with('user')
            ->latest('date')
            ->paginate(15);
        return view('admin.transactions.index', compact('transactions'));
    }

    public function savingsGoals()
    {
        $goals = SavingsGoal::with('user')
            ->latest()
            ->paginate(15);
        return view('admin.savings-goals.index', compact('goals'));
    }

    public function userDetails(User $user)
    {
        $userData = [
            'transactions' => $user->transactions()->latest('date')->take(10)->get(),
            'savings_goals' => $user->savingsGoals()->latest()->get(),
            'total_savings' => $user->transactions()->where('type', 'saving')->sum('amount'),
            'total_income' => $user->transactions()->where('type', 'income')->sum('amount'),
            'total_expenses' => $user->transactions()->where('type', 'expense')->sum('amount'),
        ];

        return view('admin.users.show', compact('user', 'userData'));
    }
} 