<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Events extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('events', function(Blueprint $table) {
        $table->increments('event_id');
        $table->string('ref_guid', 20)->unique()->nullable();
        $table->string('name');
        $table->string('description')->nullable();
        $table->dateTime('start_time')->nullable();
        $table->dateTime('end_time')->nullable();
        $table->boolean('published')->default(true);
        $table->integer('createdBy_id')->unsigned();
        $table->foreign('createdBy_id')->references('id')->on('users')->onDelete('cascade');
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
      Schema::drop('events');
    }
}
