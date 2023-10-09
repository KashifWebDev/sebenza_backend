<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockpayments', function (Blueprint $table) {
            $table->id();
            $table->integer('stock_id');
            $table->float('amount',10,2)->default(0);
            $table->date('date');
            $table->integer('userID');
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
        Schema::dropIfExists('stockpayments');
    }
};
