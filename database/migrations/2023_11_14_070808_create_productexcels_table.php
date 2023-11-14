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
        Schema::create('productexcels', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('membership_code');
            $table->date('date');
            $table->date('startDate');
            $table->date('endDate');
            $table->text('data_file')->nullable();
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
        Schema::dropIfExists('productexcels');
    }
};
