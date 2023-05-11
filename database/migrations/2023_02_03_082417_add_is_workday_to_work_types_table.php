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
        Schema::table('work_types', function (Blueprint $table) {
            $table->boolean('is_workday')->nullable()->default(0)->comment("Czy typ pracy jest dniem roboczym");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_types', function (Blueprint $table) {
            $table->dropColumn('is_workday');
        });
    }
};