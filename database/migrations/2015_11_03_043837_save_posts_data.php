<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SavePostsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('jobposts', function(Blueprint $table){
            $table->increments('jobpost_id')->index();
            $table->string('type');
            $table->string('status')->default('open');
            $table->string('title');
            $table->longText('content');
            $table->integer('user_id')->unsigned();
            $table->integer('employeeId')->unsigned();
            $table->text('location');
            $table->decimal('salary', 8, 2)->unsigned();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('jobposts');
    }
}
