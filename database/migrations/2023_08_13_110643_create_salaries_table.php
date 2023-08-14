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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->integer('user_id');
            $table->integer('payment_frequency_id');
            $table->string('payment_frequency');
            $table->string('membership_id');
            $table->decimal('basic_salaray')->default(0);
            $table->decimal('hourly_rate')->default(0);
            $table->decimal('working_hour')->default(0);
            $table->decimal('account_balance')->default(0);
            $table->decimal('pending_withdrew')->default(0);
            $table->decimal('withdrew_balance')->default(0);
            $table->string('status')->default('Active');
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
        Schema::dropIfExists('salaries');
    }
};
