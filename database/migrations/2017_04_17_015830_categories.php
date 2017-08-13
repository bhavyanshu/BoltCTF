<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Categories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('categories', function(Blueprint $table) {
        $table->increments('category_id');
        $table->integer('cat_ev_id')->unsigned();
        $table->foreign('cat_ev_id')->references('event_id')->on('events')->onDelete('cascade');
        $table->string('ref_guid', 20)->unique()->nullable();
        $table->string('category_name')->nullable();
        $table->integer('category_weight')->default('1');
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
      Schema::drop('categories');
    }
}
