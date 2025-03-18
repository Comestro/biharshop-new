<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('binary_trees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('parent_id');
            $table->enum('position', ['left', 'right']);
            $table->timestamps();

            // Add foreign key after table creation
            $table->foreign('member_id')
                  ->references('id')
                  ->on('memberships')
                  ->onDelete('cascade');
            
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('memberships')
                  ->onDelete('cascade');

            // Add unique constraints
            $table->unique('member_id');
            $table->unique(['parent_id', 'position']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('binary_trees');
    }
};
