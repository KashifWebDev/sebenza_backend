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
        Schema::create('estimatepayments', function (Blueprint $table) {
            $table->id();
            $table->integer('estimate_id');
            $table->integer('payment_methood')->nullable();
            $table->integer('amount')->default(0);
            $table->string('trx_id')->nullable();
            $table->date('date');
            $table->integer('userID');
            $table->string('status')->default('Pending');
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
        Schema::dropIfExists('estimatepayments');
    }
};
