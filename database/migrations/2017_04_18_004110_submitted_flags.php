<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubmittedFlags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('submitted_flags', function (Blueprint $table) {
        $table->increments('sbmt_id');
        $table->integer('sbmt_user_id')->unsigned();
        $table->foreign('sbmt_user_id')->references('id')->on('users')->onDelete('cascade');
        $table->integer('sbmt_que_id')->unsigned();
        $table->foreign('sbmt_que_id')->references('question_id')->on('challenge_questions')->onDelete('cascade');
        $table->string('sbmt_text')->nullabe();
        $table->integer('sbmt_points')->nullable();
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
      Schema::drop('submitted_flags');
    }
}
