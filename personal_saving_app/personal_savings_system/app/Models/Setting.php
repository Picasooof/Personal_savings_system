<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'goal_reminders',
        'goal_reminder_days',
        'budget_warnings',
        'budget_warning_threshold'
    ];

    protected $casts = [
        'goal_reminders' => 'boolean',
        'budget_warnings' => 'boolean',
        'budget_warning_threshold' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 