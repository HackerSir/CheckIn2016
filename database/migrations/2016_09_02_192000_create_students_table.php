<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->string('nid')->primary();
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('class');
            $table->string('unit_name');
            $table->string('dept_name');
            $table->integer('in_year');
            $table->string('sex');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('students');
    }
}
