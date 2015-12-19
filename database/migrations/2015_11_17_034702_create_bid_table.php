<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('jobBids', function(Blueprint $table){
            $table->increments('bidId');
            $table->integer('job_id')->unsigned();
            $table->decimal('bid', 8, 2)->unsigned();
            $table->text('comment');
            $table->integer('bidder_id')->unsigned();

            $table->foreign('job_id')->references('jobpost_id')->on('jobposts')->onDelete('cascade');
            $table->foreign('bidder_id')->references('id')->on('users');
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
        //
        Schema::drop('jobBids');
    }
}
