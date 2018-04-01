<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBoothsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booths', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned()->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->string('code');
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('types')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('booths');
    }
}
