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
            if (!Schema::hasColumn('presciption_data', 'next_visit_date')) {
                $table->string('next_visit_date')->nullable()->after('followup');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presciption_data', function (Blueprint $table) {
            if (Schema::hasColumn('presciption_data', 'next_visit_date')) {
                $table->dropColumn('next_visit_date');
            }
        });
    }
};
