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
            $table->increments('id');
            $table->bigInteger('customer_id')->unsigned()->index();
            $table->bigInteger('location_id')->unsigned()->index();
            $table->date('date');
            $table->time('time');
            $table->String('status');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('location_id')->references('id')->on('locations');
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
