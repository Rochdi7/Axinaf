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
        Schema::create('company_domain', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('company_id')
                  ->constrained('companies')
                  ->onDelete('cascade');

            $table->foreignId('domain_id')
                  ->constrained('domains')
                  ->onDelete('cascade');

            // Avoid duplicates
            $table->unique(['company_id', 'domain_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_domain');
    }
};
