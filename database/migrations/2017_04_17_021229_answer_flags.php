<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AnswerFlags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('answer_flags', function(Blueprint $table) {
        $table->increments('answer_id');
        $table->integer('ans_que_id')->unsigned();
        $table->foreign('ans_que_id')->references('question_id')->on('challenge_questions')->onDelete('cascade');
        $table->string('answer_text')->nullable();
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
      Schema::drop('answer_flags');
    }
}
