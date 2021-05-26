<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('activations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('start_date', 100)->nullable();
            $table->string('end_date', 100)->nullable();
            $table->integer('status');
            $table->integer('user_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // $table->foreign('company')->references('id')->on('companies');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activations');
    }
}
