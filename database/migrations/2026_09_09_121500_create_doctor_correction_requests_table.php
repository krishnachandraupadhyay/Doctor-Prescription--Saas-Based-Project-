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
        Schema::create('doctor_correction_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('clinic_id')->nullable();
            $table->string('field_name'); // e.g. name, phone, email, specialization, qualification, registration_number, clinic_address, other
            $table->text('current_value')->nullable(); // Kya galat hai
            $table->text('requested_value'); // Kya hona chahiye
            $table->text('reason')->nullable(); // Doctor's reason / description
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable(); // Clinic admin remarks
            $table->string('reviewed_by')->nullable(); // Clinic admin name
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_correction_requests');
    }
};
