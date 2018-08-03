<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancialdataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        ["p", "y", "d", "d1", "t8", "m4", "g3", "s6", "w", "j1", "n", "x", "j2", "v", 
        "e", "b4", "j4", "p5", "p6", "r", "r5", "s7"]; 
        
        ["p" => "Previous close", "y" => "Dividend yield", "d" => "Dividend per share", "d1" => "Last trade date", 
        "t8" => "1 year target price", "m4" => "200 day moving avg", "g3" => "Annualizd gain", "s6" => "Revenue", 
        "w" => "52 week range", "j1" => "Market capitalization", "n" => "Name", "x" => "Stock exchange", 
        "j2" => "Shares outstanding", "v" => "Volume", "e" => "EPS", "b4" => "Book value", "j4" => "EBITDA", 
        "p5" => "Price/Sales", "p6" => "Price/Book", "r" => "P/E ratio", "r5" => "PEG ratio", "s7" => "Short ratio"];
        */
        Schema::create('financialdata', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ticker')->unique();
            $table->float('p', 12, 2);
            $table->float('y', 12, 2);
            $table->float('d', 12, 2);
            $table->date('d1');
            $table->float('t8', 12, 2);
            $table->float('m4', 12, 2);
            $table->float('g3', 12, 2);
            $table->float('s6', 12, 2);
            $table->string('w');
            $table->float('j1', 12, 2);
            $table->string('n');
            $table->string('x');
            $table->float('j2', 12, 2);
            $table->float('v', 12, 2);
            $table->float('e', 12, 2);
            $table->float('b4', 12, 2);
            $table->float('j4', 12, 2);
            $table->float('p5', 12, 2);
            $table->float('p6', 12, 2);
            $table->float('r', 12, 2);
            $table->float('r5', 12, 2);
            $table->float('s7', 12, 2);
            $table->softDeletes();
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
        Schema::dropIfExists('financialdata');
    }
}
