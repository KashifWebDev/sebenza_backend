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
        Schema::create('moneytransfers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('membership_code');
            $table->date('transfer_date');
            $table->string('from');
            $table->string('to');
            $table->string('sender_name');
            $table->float('paid_amount',10,2)->default(0);
            $table->string('currency')->default('USD');
            $table->float('transfer_rate',10,2)->default(0);
            $table->float('collected_amount',10,2)->default(0);
            $table->string('receiver_name');
            $table->string('reference_code');
            $table->string('collected_status')->default('No');
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
        Schema::dropIfExists('moneytransfers');
    }
};
