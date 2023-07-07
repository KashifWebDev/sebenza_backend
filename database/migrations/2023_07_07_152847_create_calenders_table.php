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
        Schema::create('calenders', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->integer('form_id');
            $table->text('details')->nullable();
            $table->string('bgColor')->nullable();
            $table->date('startDate')->nullable();
            $table->time('startTime')->nullable();
            $table->date('endDate')->nullable();
            $table->time('endTime')->nullable();
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
        Schema::dropIfExists('calenders');
    }
};