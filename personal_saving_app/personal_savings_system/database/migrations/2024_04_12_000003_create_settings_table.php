<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('goal_reminders')->default(true);
            $table->integer('goal_reminder_days')->default(7);
            $table->boolean('budget_warnings')->default(true);
            $table->decimal('budget_warning_threshold', 5, 2)->default(80.00);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}; 