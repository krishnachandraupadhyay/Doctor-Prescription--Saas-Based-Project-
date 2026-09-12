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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_id');
            $table->string('patient_id')->unique();
            $table->string('registration')->unique();
            $table->string('patient_name');
            $table->string('guardian_type')->nullable();
            $table->string('husband_father_name')->nullable();
            $table->string('age_year')->nullable()->default(0);
            $table->string('age_month')->nullable()->default(0);
            $table->string('mobile')->nullable();
            $table->string('gender');
            $table->string('Registration_date');
            $table->string('address')->nullable();
            $table->string('aaddhar_num');
            $table->string('status')->default(true);
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};