<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChallengeQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('challenge_questions', function(Blueprint $table) {
        $table->increments('question_id');
        $table->string('ref_guid', 20)->unique()->nullable();
        $table->integer('que_cha_id')->unsigned();
        $table->foreign('que_cha_id')->references('challenge_id')->on('challenges')->onDelete('cascade');
        $table->string('question_text')->nullable();
        $table->integer('question_weight')->default('1');
        $table->integer('question_points')->nullable();
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
      Schema::drop('challenge_questions');
    }
}
