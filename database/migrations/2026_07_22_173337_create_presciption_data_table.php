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
        Schema::create('presciption_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Doctor_Emp_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('blood_pressure');
            $table->string('pulse_rate');
            $table->string('temperature');
            $table->string('blood_groups');
            $table->string('symptoms');
            $table->string('diagnosis');
            $table->foreignId('medicine_id')->constrained('medicine')->cascadeOnDelete();
            $table->string('dosage');
            $table->string('unit');
            $table->string('frequency');
            $table->string('duration');
            $table->string('advice');
            $table->string('followup');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presciption_data');
    }
};
