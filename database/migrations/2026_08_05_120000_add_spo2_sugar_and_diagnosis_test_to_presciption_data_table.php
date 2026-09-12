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
            if (!Schema::hasColumn('presciption_data', 'spo2')) {
                $table->string('spo2')->nullable()->after('temperature');
            }
            if (!Schema::hasColumn('presciption_data', 'sugar')) {
                $table->text('sugar')->nullable()->after('spo2');
            }
            if (!Schema::hasColumn('presciption_data', 'diagnosis_test')) {
                $table->text('diagnosis_test')->nullable()->after('diagnosis');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presciption_data', function (Blueprint $table) {
            if (Schema::hasColumn('presciption_data', 'diagnosis_test')) {
                $table->dropColumn('diagnosis_test');
            }
            if (Schema::hasColumn('presciption_data', 'sugar')) {
                $table->dropColumn('sugar');
            }
            if (Schema::hasColumn('presciption_data', 'spo2')) {
                $table->dropColumn('spo2');
            }
        });
    }
};
