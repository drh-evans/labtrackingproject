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
        Schema::create('lab_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('created_by');
            $table->string('task_name');
            $table->text('description');
            $table->unsignedInteger('total_points');
            $table->dateTime('fm_due_date'); // full marks
            $table->dateTime('hm_due_date'); // half marks
            $table->timestamps();

            $table->foreign('module_id')
            ->references('id')
            ->on('modules')
            ->cascadeOnDelete();
            $table->foreign('created_by')
            ->references('id')
            ->on('users')
            ->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_tasks');
    }
};
