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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoiceID');
            $table->integer('order_id');
            $table->integer('account_total_user');
            $table->decimal('cost_per_user')->default(0);
            $table->decimal('amount_total')->default(0);
            $table->decimal('discount')->default(0);
            $table->decimal('payable_amount')->default(0);
            $table->decimal('paid_amount')->default(0);
            $table->string('payment_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->longText('payment_response')->nullable();
            $table->date('invoiceDate');
            $table->date('paymentDate')->nullable();
            $table->string('status')->default('Unpaid');
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
        Schema::dropIfExists('invoices');
    }
};
