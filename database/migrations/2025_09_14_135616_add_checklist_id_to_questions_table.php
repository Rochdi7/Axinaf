<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('questions', function (Blueprint $table) {
        $table->unsignedBigInteger('checklist_id')->nullable()->after('id'); // adjust position
        $table->foreign('checklist_id')->references('id')->on('checklists')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('questions', function (Blueprint $table) {
        $table->dropForeign(['checklist_id']);
        $table->dropColumn('checklist_id');
    });
}

};
