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
        Schema::table('doctors', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('doctors', 'clinic_stamp')) {
                $columnsToDrop[] = 'clinic_stamp';
            }
            if (Schema::hasColumn('doctors', 'logo')) {
                $columnsToDrop[] = 'logo';
            }
            if (Schema::hasColumn('doctors', 'clinic_address')) {
                $columnsToDrop[] = 'clinic_address';
            }
            if (Schema::hasColumn('doctors', 'has_member')) {
                $columnsToDrop[] = 'has_member';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            if (!Schema::hasColumn('doctors', 'clinic_stamp')) {
                $table->string('clinic_stamp')->nullable();
            }
            if (!Schema::hasColumn('doctors', 'logo')) {
                $table->string('logo')->nullable();
            }
            if (!Schema::hasColumn('doctors', 'clinic_address')) {
                $table->text('clinic_address')->nullable();
            }
            if (!Schema::hasColumn('doctors', 'has_member')) {
                $table->boolean('has_member')->default(true);
            }
        });
    }
};
