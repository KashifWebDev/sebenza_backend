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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('membership_code');
            $table->string('asset_name');
            $table->text('asset_description')->nullable();
            $table->integer('quantity');
            $table->date('purchese_date');
            $table->float('purchese_value',10,2)->default(0);
            $table->string('currency')->default('USD');
            $table->integer('quantity');
            $table->date('capture_date');
            $table->string('capture_name');
            $table->text('attachment')->nullable();
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
        Schema::dropIfExists('assets');
    }
};
