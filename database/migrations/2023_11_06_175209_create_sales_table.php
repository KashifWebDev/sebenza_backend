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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('membership_id');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_address')->nullable();
            $table->decimal('amount_total',10,2)->default(0);
            $table->decimal('discount',10,2)->default(0);
            $table->decimal('payable_amount',10,2)->default(0);
            $table->decimal('due',10,2)->default(0);
            $table->string('payment_type')->nullable();
            $table->string('trx_id')->nullable();
            $table->date('payment_date')->nullable();
            $table->decimal('paid_amount',10,2)->default(0);
            $table->date('orderDate');
            $table->text('comment')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('sales');
    }
};
