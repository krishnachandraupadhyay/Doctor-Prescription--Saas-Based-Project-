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
        Schema::table('presciption_data', function (Blueprint $table) {
            $table->string('blood_pressure')->nullable()->change();
            $table->string('pulse_rate')->nullable()->change();
            $table->string('temperature')->nullable()->change();
            $table->string('blood_groups')->nullable()->change();
            $table->text('symptoms')->nullable()->change();
            $table->text('diagnosis')->nullable()->change();
            $table->unsignedBigInteger('medicine_id')->nullable()->change();
            $table->string('dosage')->nullable()->change();
            $table->string('unit')->nullable()->change();
            $table->string('frequency')->nullable()->change();
            $table->string('duration')->nullable()->change();
            $table->text('advice')->nullable()->change();
            $table->text('followup')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presciption_data', function (Blueprint $table) {
            //
        });
    }
};
