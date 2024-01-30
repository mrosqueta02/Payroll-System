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
        Schema::create('timekeepings', function (Blueprint $table) {
            $table->id();
            $table->string('EmployeeID');
            $table->string('EmployeeName');
            $table->timestamp('TimeIn')->nullable();
            $table->timestamp('TimeOut')->nullable();
            $table->timestamp('LateArrival')->nullable();
            $table->decimal('Overtime', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timekeepings');
    }
};
