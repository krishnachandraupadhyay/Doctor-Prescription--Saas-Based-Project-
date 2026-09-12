<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctor_clinic_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('doctor_clinic_documents', 'clinic_id')) {
                $table->foreignId('clinic_id')->nullable()->after('id')->constrained('clinics')->nullOnDelete();
            }
        });

        // Make doctor_id nullable if it exists
        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE doctor_clinic_documents MODIFY doctor_id BIGINT UNSIGNED NULL');
        } catch (\Throwable $e) {
            // Ignore if already nullable or different driver
        }
    }

    public function down(): void
    {
        Schema::table('doctor_clinic_documents', function (Blueprint $table) {
            if (Schema::hasColumn('doctor_clinic_documents', 'clinic_id')) {
                $table->dropForeign(['clinic_id']);
                $table->dropColumn('clinic_id');
            }
        });
    }
};
