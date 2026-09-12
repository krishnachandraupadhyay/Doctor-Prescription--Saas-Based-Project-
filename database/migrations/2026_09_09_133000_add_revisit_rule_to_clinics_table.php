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
        Schema::table('clinics', function (Blueprint $table) {
            if (!Schema::hasColumn('clinics', 'has_revisit_rule')) {
                $table->boolean('has_revisit_rule')->default(false)->after('has_deleted_staff');
            }
            if (!Schema::hasColumn('clinics', 'revisit_validity_days')) {
                $table->integer('revisit_validity_days')->default(7)->after('has_revisit_rule');
            }
            if (!Schema::hasColumn('clinics', 'revisit_fee_type')) {
                $table->string('revisit_fee_type', 20)->default('free')->after('revisit_validity_days'); // 'free' or 'paid'
            }
            if (!Schema::hasColumn('clinics', 'revisit_discount_percent')) {
                $table->integer('revisit_discount_percent')->default(100)->after('revisit_fee_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $columns = ['has_revisit_rule', 'revisit_validity_days', 'revisit_fee_type', 'revisit_discount_percent'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('clinics', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
