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
        Schema::create('taxations', function (Blueprint $table) {
            $table->id();
            $table->string('EmployeeID');
            $table->string('EmployeeName');
            $table->float('SSS')->nullable();
            $table->float('PAGIBIG')->nullable();
            $table->float('PHILHEALTH')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxations');
    }
};
