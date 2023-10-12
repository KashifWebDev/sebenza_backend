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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('membership_code');
            $table->date('date');
            $table->string('sender_name');
            $table->string('sender_cell_number')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('sender_address')->nullable();
            $table->string('receiver_name');
            $table->string('receiver_cell_number')->nullable();
            $table->string('receiver_email')->nullable();
            $table->string('receiver_address')->nullable();
            $table->string('reference')->nullable();
            $table->string('code')->nullable();
            $table->float('item_value',10,2)->default(0);
            $table->float('discount',10,2)->default(0);
            $table->float('total_amount',10,2)->default(0);
            $table->float('paid_amount',10,2)->default(0);
            $table->float('due_amount',10,2)->default(0);
            $table->text('invoice_image')->nullable();
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
        Schema::dropIfExists('stocks');
    }
};
