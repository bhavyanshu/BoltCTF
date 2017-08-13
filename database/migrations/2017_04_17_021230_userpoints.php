<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Userpoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('userpoints', function (Blueprint $table) {
        $table->increments('upt_id');
        $table->integer('upt_user_id')->unsigned();
        $table->foreign('upt_user_id')->references('id')->on('users')->onDelete('cascade');
        $table->integer('upt_ev_id')->unsigned();
        $table->foreign('upt_ev_id')->references('event_id')->on('events')->onDelete('cascade');
        $table->integer('upt_points')->nullabe();
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
      Schema::drop('userpoints');
    }
}
