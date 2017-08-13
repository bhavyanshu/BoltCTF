<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Profiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('profiles', function(Blueprint $table)
      {
        $table->increments('prof_id');
        $table->integer('user_id')->unsigned();
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->string('first_name')->nullable();
        $table->string('last_name')->nullable();
        $table->string('profpic')->nullable();
        $table->string('mobilenumber')->nullable();
        $table->string('address')->nullable();
        $table->string('pincode')->nullable();
        $table->string('gender')->nullable();
        $table->string('bio')->nullable();
        $table->string('occupation')->nullable();
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
      Schema::drop('profiles');
    }
}
