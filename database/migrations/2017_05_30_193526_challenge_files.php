<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChallengeFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('challenge_files', function(Blueprint $table)
      {
        $table->increments('f_id');
        $table->integer('user_f_id')->unsigned();
        $table->foreign('user_f_id')->references('id')->on('users')->onDelete('cascade');
        $table->integer('cha_f_id')->unsigned();
        $table->foreign('cha_f_id')->references('challenge_id')->on('challenges')->onDelete('cascade');
        $table->string('f_name')->nullable();
        $table->string('f_token')->nullable();
        $table->string('f_status')->default(true);//1->visible, 2->invisible
        $table->timestamps('created_at');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('challenge_files');
    }
}
