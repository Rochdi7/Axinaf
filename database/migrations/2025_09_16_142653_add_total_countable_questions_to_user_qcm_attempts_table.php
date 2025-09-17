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
    Schema::table('user_qcm_attempts', function (Blueprint $table) {
        $table->unsignedInteger('total_countable_questions')->default(0)->after('score');
    });
}

public function down(): void
{
    Schema::table('user_qcm_attempts', function (Blueprint $table) {
        $table->dropColumn('total_countable_questions');
    });
}
};
