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
        Schema::create('lab_task_users', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('lab_task_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('marks_awarded')->default(0);
            $table->unsignedBigInteger('signed_off_by');
            $table->timestamp('completed_at');
            $table->timestamps();

            $table->foreign('lab_task_id')
            ->references('id')
            ->on('lab_tasks')
            ->cascadeOnDelete();

            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->cascadeOnDelete();

            $table->foreign('signed_off_by')
            ->references('id')
            ->on('users')
            ->cascadeOnDelete();
            $table->unique(['lab_task_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_user');
    }
};
