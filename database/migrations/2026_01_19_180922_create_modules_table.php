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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('module_code'); // i.e. CSC-348
            $table->string('module_name'); // i.e. Web Applications Development
            $table->unsignedBigInteger('module_leader_id')->nullable(); // i.e. 1 (Lecturers ID)
            $table->unsignedBigInteger('year_taught'); // academic year taught
            $table->unsignedBigInteger('year_group'); // 1
            $table->string('bg_image_url')->nullable();
            $table->timestamps();

            $table->foreign('module_leader_id')
            ->references('id')
            ->on('users')
            ->nullOnDelete();
            
            $table->unique(['module_code', 'year_taught']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
