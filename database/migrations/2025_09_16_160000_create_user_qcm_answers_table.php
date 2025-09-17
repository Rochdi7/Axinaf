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
        Schema::create('user_qcm_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_qcm_attempt_id');
            $table->unsignedBigInteger('question_id');
            $table->enum('status', ['Conforme', 'Non conforme', 'Non applicable', 'En attente']);
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_qcm_attempt_id')
                  ->references('id')
                  ->on('user_qcm_attempts')
                  ->onDelete('cascade');

            $table->foreign('question_id')
                  ->references('id')
                  ->on('questions')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_qcm_answers');
    }
};
