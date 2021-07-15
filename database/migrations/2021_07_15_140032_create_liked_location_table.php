<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikedLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liked_location', function (Blueprint $table) {
            $table->bigInteger('customer_id');
            $table->bigInteger('location_id');
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
        Schema::dropIfExists('liked_location');
    }
}
