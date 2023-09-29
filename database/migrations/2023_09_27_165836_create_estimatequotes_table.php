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
        Schema::create('estimatequotes', function (Blueprint $table) {
            $table->id();
            $table->string('estimateID');
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_zone')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->text('customer_e_signature')->nullable();
            $table->float('subTotal',10,2)->default(0);
            $table->float('discountCharge',10,2)->default(0);
            $table->float('vat',10,2)->default(0);
            $table->float('total',10,2)->default(0);
            $table->date('entryDate');
            $table->date('doneDate')->nullable();
            $table->date('last_updated')->nullable();
            $table->date('paymentDate')->nullable();
            $table->integer('user_id');
            $table->string('membership_code');
            $table->string('status');
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
        Schema::dropIfExists('estimatequotes');
    }
};
