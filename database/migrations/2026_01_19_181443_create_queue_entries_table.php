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
        Schema::create('queue_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('queue_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedInteger('queue_position')->nullable();
            $table->enum('assistance_type', ['Assistance', 'Sign-off'])->default('Sign-off');
            $table->timestamp('joined_at');
            $table->timestamp('left_at')->nullable();
            $table->timestamps();

            $table->foreign('queue_id')->references('id')->on('queues')->cascadeOnDelete();
            $table->foreign('student_id')->references('id')->on('users')->cascadeOnDelete();

            // ensures that the queue position is unique per queue
            $table->unique(['queue_id', 'queue_position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_entries');
    }
};
