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
        Schema::create('withdrews', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('membership_id');
            $table->string('payment_method');
            $table->string('account_name')->nullable();
            $table->string('account_number');
            $table->text('additional_info')->nullable();
            $table->decimal('amount')->default(0);
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
        Schema::dropIfExists('withdrews');
    }
};
