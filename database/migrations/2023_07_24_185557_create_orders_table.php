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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('membership_id');
            $table->string('account_type')->nullable();
            $table->integer('account_type_id')->nullable();
            $table->integer('account_total_user');  // Total
            $table->integer('new_user')->default(0);  // Total
            $table->decimal('cost_per_user')->default(0);
            $table->decimal('amount_total')->default(0);
            $table->date('orderDate');  // Order Date
            $table->date('expireDate')->nullable(); // Complete Date
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
        Schema::dropIfExists('orders');
    }
};
