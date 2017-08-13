<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncorrectFlags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('incorrect_flags', function (Blueprint $table) {
        $table->increments('if_id');
        $table->integer('if_user_id')->unsigned();
        $table->foreign('if_user_id')->references('id')->on('users')->onDelete('cascade');
        $table->integer('if_que_id')->unsigned();
        $table->foreign('if_que_id')->references('question_id')->on('challenge_questions')->onDelete('cascade');
        $table->integer('if_ev_id')->unsigned();
        $table->foreign('if_ev_id')->references('event_id')->on('events')->onDelete('cascade');
        $table->string('if_text')->nullabe();
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
      Schema::drop('incorrect_flags');
    }
}
