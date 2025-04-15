<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('savings_goals', function (Blueprint $table) {
            $table->decimal('target_amount', 20, 2)->change();
            $table->decimal('saved_amount', 20, 2)->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('savings_goals', function (Blueprint $table) {
            $table->decimal('target_amount', 10, 2)->change();
            $table->decimal('saved_amount', 10, 2)->default(0)->change();
        });
    }
}; 