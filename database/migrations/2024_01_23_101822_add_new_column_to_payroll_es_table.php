<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payroll_e_s', function (Blueprint $table) {
            
            $table->decimal('BasicSalary', 8, 2)->nullable();
            $table->decimal('Tax', 8, 2)->nullable();
            $table->decimal('MonthlyGrossIncome', 8,2)->nullable();
            $table->integer('TotalDays')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_e_s', function (Blueprint $table) {
            //
        });
    }
};
