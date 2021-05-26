<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivationReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activation_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('activation_id')->nullable();
            $table->string('what_worked', 100)->nullable();
            $table->string('what_failed', 100)->nullable();
            $table->string('feedback', 100)->nullable();
            $table->string('cover_image')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('activation_reports');
    }
}
