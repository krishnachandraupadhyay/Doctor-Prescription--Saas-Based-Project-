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
      Schema::create('doctors', function (Blueprint $table) {
    $table->id();
    $table->string('Doctor_Emp_id')->unique();
    // Admin fills these
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->string('clinic_name');

    // Doctor fills these later
    $table->string('qualification')->nullable();
    $table->string('specialization')->nullable();
    $table->string('registration_number')->nullable()->unique();
    $table->text('clinic_address')->nullable();
    $table->string('phone', 15)->nullable();
    $table->string('Experience')->nullable();
    $table->string('license_number')->nullable();

    $table->string('logo')->nullable();
    $table->string('signature')->nullable();
    $table->string('clinic_stamp')->nullable();
    $table->string('prescription_type');
    // Profile completion
    $table->boolean('profile_completed')->default(false);
    $table->boolean('verified')->default(0);
    // Account status
    $table->boolean('status')->default(true);
    $table->string('created_by')->nullable();
    $table->string('updated_by')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
        $table->dropColumn('prescription_type');
    }
};
