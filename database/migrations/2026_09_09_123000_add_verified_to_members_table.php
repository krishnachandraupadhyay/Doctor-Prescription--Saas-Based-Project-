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
        Schema::table('members', function (Blueprint $table) {
            if (!Schema::hasColumn('members', 'verified')) {
                $table->boolean('verified')->default(0)->after('status');
            }
            if (!Schema::hasColumn('members', 'verified_by')) {
                $table->string('verified_by')->nullable()->after('verified');
            }
            if (!Schema::hasColumn('members', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verified_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'verified_at')) {
                $table->dropColumn('verified_at');
            }
            if (Schema::hasColumn('members', 'verified_by')) {
                $table->dropColumn('verified_by');
            }
            if (Schema::hasColumn('members', 'verified')) {
                $table->dropColumn('verified');
            }
        });
    }
};
