<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('type')->change();
        });

        // Update the type column to use enum values
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('income', 'expense', 'saving')");
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('type', ['income', 'expense'])->change();
        });
    }
}; 