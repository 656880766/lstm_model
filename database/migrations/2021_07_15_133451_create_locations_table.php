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
            $table->increments('id');
            $table->String('place');
            $table->String('name');
            $table->String('description')->nullable();
            $table->String('note_average')->nullable();
            $table->String('stars')->nullable();
            $table->String('image')->nullable();
            $table->String('owner_name');
            $table->bigInteger('likes')->default(0)->nullable();
            $table->String('owner_phone');
            $table->String('status')->default('0');

            $table->bigInteger('category_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('categories');

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
        Schema::dropIfExists('locations');
    }
}
