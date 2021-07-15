<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReserveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserve', function (Blueprint $table) {
            $table->increments('reserve_id');
            $table->bigInteger('customer_id')->unsigned();
            $table->bigInteger('location_id')->unsigned();
            $table->date('date');
            $table->time('time');
            $table->String('status');
            $table->foreign('customer_id')->references('customer_id')->on('customer');
            $table->foreign('location_id')->references('location_id')->on('location');
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
        Schema::dropIfExists('reserve');
    }
}
