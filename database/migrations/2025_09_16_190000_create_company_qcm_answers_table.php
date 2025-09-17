<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_qcm_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('domain_id')->constrained('domains')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');

            $table->enum('status', ['Confirmer', 'Non confirmer', 'En cours', 'Non applicable'])->default('En cours');

            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');

            $table->timestamps();

            // Avoid duplicate answers with a short unique name
            $table->unique(['user_id', 'company_id', 'domain_id', 'question_id'], 'uq_company_qcm_answers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_qcm_answers');
    }
};
