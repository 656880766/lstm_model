<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->String('description');
            $table->bigInteger('sender_id')->unsigned()->index();
            $table->bigInteger('receiver_id')->unsigned()->index();
            $table->date('start_day')->default('0000-00-00');
            $table->date('finish_day')->default('0000-00-00');
            $table->String('status')->default('0');
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
        Schema::dropIfExists('notification');
    }
}
