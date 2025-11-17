<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('epins', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('plan_amount', 10, 2)->default(0);
            $table->string('plan_name')->nullable();
            $table->foreignId('owner_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('generated_by_admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->foreignId('used_by_membership_id')->nullable()->constrained('memberships')->onDelete('set null');
            $table->enum('status', ['available','transferred','used'])->default('available');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('epins');
    }
};

