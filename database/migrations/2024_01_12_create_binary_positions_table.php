<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('binary_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_id')->constrained();
            $table->foreignId('parent_id')->nullable()->constrained('memberships');
            $table->enum('position', ['left', 'right']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('binary_positions');
    }
};
