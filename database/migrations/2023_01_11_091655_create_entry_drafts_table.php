<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_type_id')->constrained('work_types');
            $table->foreignId('project_id')->constrained('projects');
            $table->foreignId('timestamp_id')->constrained('timestamp_drafts')->onDelete('cascade');
            $table->integer('working_hours');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entry_drafts');
    }
};
