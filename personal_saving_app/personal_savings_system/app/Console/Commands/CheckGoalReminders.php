<?php

namespace App\Console\Commands;

use App\Models\SavingsGoal;
use App\Models\User;
use App\Notifications\GoalReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CheckGoalReminders extends Command
{
    protected $signature = 'goals:check-reminders';
    protected $description = 'Check for goals that need reminders and send notifications';

    public function handle()
    {
        $users = User::with(['savingsGoals', 'setting'])->get();

        foreach ($users as $user) {
            if (!$user->setting->goal_reminders) {
                continue;
            }

            $goals = $user->savingsGoals()
                ->where('is_completed', false)
                ->where('updated_at', '<', Carbon::now()->subDays($user->setting->goal_reminder_days))
                ->get();

            foreach ($goals as $goal) {
                $user->notify(new GoalReminder($goal));
            }
        }

        $this->info('Goal reminders checked and notifications sent.');
    }
} 