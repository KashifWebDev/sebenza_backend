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
        Schema::create('serveices', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('membership_code');
            $table->string('title');
            $table->string('image');
            $table->float('regular_price')->default(0);
            $table->float('discount')->default(0);
            $table->float('net_price')->default(0);
            $table->string('status');
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
        Schema::dropIfExists('serveices');
    }
};
