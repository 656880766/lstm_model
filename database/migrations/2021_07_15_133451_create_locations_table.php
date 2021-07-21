<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('location_id');

            $table->String('place');
            $table->String('name');
            $table->String('description');
            $table->String('note_average');
            $table->String('stars');
            $table->String('image');
            $table->String('state');
            $table->String('owner_name');
            $table->String('owner_phone');
            $table->bigInteger('category_Id')->unsigned()->index;
            $table->foreign('category_Id')->references('category_Id')->on('category');

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
        Schema::dropIfExists('location');
    }
}
