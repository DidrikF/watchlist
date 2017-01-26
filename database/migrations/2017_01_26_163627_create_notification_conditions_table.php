<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('notification_id')->unsigned();
            $table->string('data_id');
            $table->string('comparison_operator');
            $table->string('data_value');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_conditions');
    }
}
