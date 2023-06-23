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
        Schema::create('metings', function (Blueprint $table) {
            $table->id();
            $table->integer('form_id');
            $table->text('title');
            $table->text('place')->nullable();
            $table->text('description')->nullable();
            $table->text('link')->nullable();
            $table->text('recipients')->nullable();
            $table->date('date');
            $table->time('time');
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
        Schema::dropIfExists('metings');
    }
};