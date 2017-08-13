<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Challenges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('challenges', function(Blueprint $table) {
        $table->increments('challenge_id');
        $table->integer('cha_cat_id')->unsigned();
        $table->foreign('cha_cat_id')->references('category_id')->on('categories')->onDelete('cascade');
        $table->string('ref_guid', 20)->unique()->nullable();
        $table->string('challenge_name')->nullable();
        $table->integer('challenge_weight')->default('1');
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
      Schema::drop('challenges');
    }
}
