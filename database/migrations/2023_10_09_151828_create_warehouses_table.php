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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('membership_code');
            $table->string('title');
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->text('location')->nullable();
            $table->integer('total_qty')->default(0);
            $table->integer('total_transfer')->default(0);
            $table->integer('available_qty')->default(0);
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
        Schema::dropIfExists('warehouses');
    }
};
