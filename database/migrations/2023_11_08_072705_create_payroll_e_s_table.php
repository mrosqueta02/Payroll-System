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
        Schema::create('payroll_e_s', function (Blueprint $table) {
            $table->id();
            $table->string('EmployeeID');
            $table->string('EmployeeName');
            $table->string('Salary');
            $table->string('RPH');
            $table->string('TotalHrs');
            $table->string('NetIncome');
            $table->string('SSS')->nullable();
            $table->string('PHILHEALTH')->nullable();
            $table->string('PAGIBIG')->nullable();
            $table->string('TotalDeduction');
            $table->string('Benefits')->nullable();
            $table->string('GrossIncome');
            $table->decimal('Overtime', 8, 2)->nullable();
            $table->integer('week_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_e_s');
    }
};
