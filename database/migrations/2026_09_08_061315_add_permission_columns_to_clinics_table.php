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
            $table->boolean('has_member')->default(true)->after('prescription_type');
            $table->boolean('has_payment_category')->default(false)->after('has_member');
            $table->boolean('has_deleted_staff')->default(false)->after('has_payment_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn(['has_member', 'has_payment_category', 'has_deleted_staff']);
        });
    }
};
