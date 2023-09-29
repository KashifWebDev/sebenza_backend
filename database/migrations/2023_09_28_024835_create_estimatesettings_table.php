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
        Schema::create('estimatesettings', function (Blueprint $table) {
            $table->id();
            $table->integer('template_id')->default(1);
            $table->text('logo')->nullable();
            $table->string('color_code')->nullable();
            $table->string('font_name')->nullable();
            $table->text('e_signature')->nullable();
            $table->integer('user_id');
            $table->string('membership_code');
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
        Schema::dropIfExists('estimatesettings');
    }
};
