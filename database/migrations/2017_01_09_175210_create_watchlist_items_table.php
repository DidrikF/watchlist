<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWatchlistItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watchlist_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('watchlist_id')->unsigned();
            //I want to use the ticker as the foreign key to link to the companies table
            $table->string('ticker'); //company needs to be added to the companies table once someone adds the company to a watchlist.
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('watchlist_id')->references('id')->on('watchlists')->onDelete('cascade');
            $table->foreign('ticker')->references('ticker')->on('companies');

            //get analysis by analysis where(watchlist_id->user_id + ticker) 
            //company data to display the watchlist entry will be gotten from the companies table, so we need to store company data when a company is added to a watchlist.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('watchlist_items');
    }
}
