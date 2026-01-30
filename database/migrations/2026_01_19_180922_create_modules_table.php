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
            $table->string('module_code')->unique(); // i.e. CSC-348
            $table->string('module_name'); // i.e. Web Applications Development
            $table->unsignedBigInteger('module_leader')->nullable(); // i.e. 1 (Lecturers ID)
            $table->year('year_taught')->nullable();
            $table->unsignedBigInteger('teaching_block')->nullable();
            $table->string('bg_image_url')->nullable();
            $table->timestamps();
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
