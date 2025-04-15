<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, convert the column to string to allow any value
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('type')->change();
        });
    }

    public function down()
    {
        // Convert back to enum with original values
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('type', ['income', 'expense'])->change();
        });
    }
}; 