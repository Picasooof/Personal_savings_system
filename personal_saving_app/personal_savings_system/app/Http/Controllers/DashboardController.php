<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\SavingsGoal;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        try {
            // Get total savings from all savings transactions
            $totalSavings = Transaction::where('user_id', $user->id)
                ->where('type', 'saving')
                ->sum('amount');
                
            // Get active goals count and next goal
            $activeGoals = SavingsGoal::where('user_id', $user->id)
                ->where('is_completed', false)
                ->get();
                
            $activeGoalsCount = $activeGoals->count();
                
            $nextGoal = $activeGoals
                ->where('deadline', '>', now())
                ->sortBy('deadline')
                ->first();

            // Get additional dashboard data
            $monthlySummary = $this->getMonthlySummary();
            $spendingByCategory = $this->getSpendingByCategory();
            $savingsProgress = $this->getSavingsProgress();
            $recentTransactions = $this->getRecentTransactions();
            $availableSavingsGoals = $this->getAvailableSavingsGoals();

            return view('dashboard', [
                'totalSavings' => $totalSavings,
                'activeGoalsCount' => $activeGoalsCount,
                'nextGoal' => $nextGoal,
                'monthlySummary' => $monthlySummary,
                'spendingByCategory' => $spendingByCategory,
                'savingsProgress' => $savingsProgress,
                'recentTransactions' => $recentTransactions,
                'availableSavingsGoals' => $availableSavingsGoals
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Dashboard data fetch error: ' . $e->getMessage());
            
            // Return view with error message
            return view('dashboard')->with('error', 'Unable to load dashboard data. Please try again later.');
        }
    }

    private function getMonthlySummary()
    {
        $user = Auth::user();
        $currentMonth = now()->startOfMonth();
        $nextMonth = now()->addMonth()->startOfMonth();

        return [
            'income' => Transaction::where('user_id', $user->id)
                ->where('type', 'income')
                ->whereBetween('date', [$currentMonth, $nextMonth])
                ->sum('amount'),
            'expenses' => Transaction::where('user_id', $user->id)
                ->where('type', 'expense')
                ->whereBetween('date', [$currentMonth, $nextMonth])
                ->sum('amount'),
            'savings' => SavingsGoal::where('user_id', $user->id)
                ->where('is_completed', false)
                ->sum('saved_amount')
        ];
    }

    private function getSpendingByCategory()
    {
        $user = Auth::user();
        return Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();
    }

    private function getSavingsProgress()
    {
        $user = Auth::user();
        return SavingsGoal::where('user_id', $user->id)
            ->select(
                DB::raw('SUM(target_amount) as target_total'),
                DB::raw('SUM(saved_amount) as saved_total')
            )
            ->where('is_completed', false)
            ->first();
    }

    /**
     * Store a new transaction
     */
    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        try {
            $transaction = new Transaction();
            $transaction->user_id = Auth::id();
            $transaction->amount = $validated['amount'];
            $transaction->type = $validated['type'];
            $transaction->category = $validated['category'];
            $transaction->description = $validated['description'];
            $transaction->date = $validated['date'];
            $transaction->save();

            return redirect()->back()->with('success', 'Transaction added successfully!');
        } catch (\Exception $e) {
            Log::error('Transaction creation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add transaction. Please try again.');
        }
    }

    /**
     * Get recent transactions
     */
    public function getRecentTransactions()
    {
        try {
            return Transaction::where('user_id', Auth::id())
                ->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')  // Secondary sort by creation time
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching recent transactions: ' . $e->getMessage());
            return collect([]); // Return empty collection if there's an error
        }
    }

    public function storeSaving(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'goal_id' => 'required|exists:savings_goals,id'
        ]);

        try {
            DB::beginTransaction();

            // Create a savings transaction
            $transaction = new Transaction();
            $transaction->user_id = Auth::id();
            $transaction->amount = $validated['amount'];
            $transaction->type = 'saving';
            $transaction->category = 'Savings';
            $transaction->description = $validated['description'];
            $transaction->date = now();
            $transaction->save();

            // Update the savings goal
            $savingsGoal = SavingsGoal::find($validated['goal_id']);
            $savingsGoal->saved_amount += $validated['amount'];
            $savingsGoal->is_completed = $savingsGoal->saved_amount >= $savingsGoal->target_amount;
            $savingsGoal->save();

            DB::commit();
            return redirect()->back()->with('success', 'Saving added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Saving creation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add saving. Please try again.');
        }
    }

    /**
     * Get available savings goals for dropdown
     */
    private function getAvailableSavingsGoals()
    {
        return SavingsGoal::where('user_id', Auth::id())
            ->where('is_completed', false)
            ->get();
    }
} 