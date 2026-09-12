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
            if (!Schema::hasColumn('presciption_data', 'payment_category_id')) {
                $table->foreignId('payment_category_id')
                      ->nullable()
                      ->after('Doctor_Emp_id')
                      ->constrained('payment_categories')
                      ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presciption_data', function (Blueprint $table) {
            if (Schema::hasColumn('presciption_data', 'payment_category_id')) {
                $table->dropForeign(['payment_category_id']);
                $table->dropColumn('payment_category_id');
            }
        });
    }
};
