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
        Schema::create('invoicefroms', function (Blueprint $table) {
            $table->id();
            $table->string('membership_code');
            $table->boolean('invoice_for')->default(0);
            $table->integer('user_id')->nullable();
            $table->string('invoiceID');
            $table->text('logo')->nullable();
            $table->text('name')->nullable();
            $table->text('email')->nullable();
            $table->text('company_name')->nullable();
            $table->text('address')->nullable();
            $table->longText('invoice_details')->nullable();
            $table->decimal('amount_total')->default(0);
            $table->decimal('discount')->default(0);
            $table->decimal('payable_amount')->default(0);
            $table->decimal('paid_amount')->default(0);
            $table->string('payment_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->longText('payment_response')->nullable();
            $table->date('invoiceDate');
            $table->date('paymentDate')->nullable();
            $table->string('status')->default('Draft');
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
        Schema::dropIfExists('invoicefroms');
    }
};
