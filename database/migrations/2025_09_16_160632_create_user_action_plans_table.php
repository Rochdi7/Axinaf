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
        Schema::create('user_action_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_qcm_answer_id')->constrained('user_qcm_answers')->onDelete('cascade');
            $table->text('action_text');
            $table->string('responsible_name')->nullable(); // Changed to a string/varchar
            $table->date('deadline')->nullable();
            $table->text('evaluation')->nullable();
            $table->timestamps();

            // To ensure a unique action plan per answer
            $table->unique('user_qcm_answer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_action_plans');
    }
};
