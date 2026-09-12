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
        if (Schema::hasTable('medicine') && !Schema::hasColumn('medicine', 'medicine_code')) {
            Schema::table('medicine', function (Blueprint $table) {
                $table->string('medicine_code', 50)->nullable()->after('id')->index();
            });
        }

        if (Schema::hasTable('symptoms') && !Schema::hasColumn('symptoms', 'symptom_code')) {
            Schema::table('symptoms', function (Blueprint $table) {
                $table->string('symptom_code', 50)->nullable()->after('id')->index();
            });
        }

        if (Schema::hasTable('suggestion')) {
            Schema::table('suggestion', function (Blueprint $table) {
                if (!Schema::hasColumn('suggestion', 'suggestion_code')) {
                    $table->string('suggestion_code', 50)->nullable()->after('id')->index();
                }
                if (!Schema::hasColumn('suggestion', 'created_by')) {
                    $table->string('created_by')->nullable()->after('description');
                }
                if (!Schema::hasColumn('suggestion', 'updated_by')) {
                    $table->string('updated_by')->nullable()->after('created_by');
                }
            });
        }

        if (Schema::hasTable('diagnosis_tests') && !Schema::hasColumn('diagnosis_tests', 'test_code')) {
            Schema::table('diagnosis_tests', function (Blueprint $table) {
                $table->string('test_code', 50)->nullable()->after('id')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('medicine') && Schema::hasColumn('medicine', 'medicine_code')) {
            Schema::table('medicine', function (Blueprint $table) {
                $table->dropColumn('medicine_code');
            });
        }

        if (Schema::hasTable('symptoms') && Schema::hasColumn('symptoms', 'symptom_code')) {
            Schema::table('symptoms', function (Blueprint $table) {
                $table->dropColumn('symptom_code');
            });
        }

        if (Schema::hasTable('suggestion')) {
            Schema::table('suggestion', function (Blueprint $table) {
                if (Schema::hasColumn('suggestion', 'suggestion_code')) {
                    $table->dropColumn('suggestion_code');
                }
                if (Schema::hasColumn('suggestion', 'created_by')) {
                    $table->dropColumn('created_by');
                }
                if (Schema::hasColumn('suggestion', 'updated_by')) {
                    $table->dropColumn('updated_by');
                }
            });
        }

        if (Schema::hasTable('diagnosis_tests') && Schema::hasColumn('diagnosis_tests', 'test_code')) {
            Schema::table('diagnosis_tests', function (Blueprint $table) {
                $table->dropColumn('test_code');
            });
        }
    }
};
