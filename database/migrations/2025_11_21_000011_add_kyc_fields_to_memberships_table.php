<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->string('pancard_image')->nullable();
            $table->string('aadhar_card_image')->nullable();
            $table->enum('kyc_status', ['pending','approved','rejected'])->default('pending');
            $table->timestamp('kyc_verified_at')->nullable();
            $table->string('kyc_rejection_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn(['pancard_image','aadhar_card_image','kyc_status','kyc_verified_at','kyc_rejection_reason']);
        });
    }
};

