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
        $table->enum('status', ['in_progress', 'completed'])->default('in_progress')->after('checklist_id');
    });
}

public function down(): void
{
    Schema::table('user_qcm_attempts', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};
