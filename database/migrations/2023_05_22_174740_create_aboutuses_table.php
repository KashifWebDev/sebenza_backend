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
        Schema::create('aboutuses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('text')->nullable();
            $table->text('image')->nullable();
            $table->text('short_description')->nullable();
            $table->string('short_title')->nullable();
            $table->text('banner_image')->nullable();

            //middle text
            $table->string('m_title')->nullable();
            $table->text('m_text')->nullable();
            $table->text('m_text_two')->nullable();

            //text one
            $table->string('title_one')->nullable();
            $table->text('text_one')->nullable();
            //text two
            $table->string('title_two')->nullable();
            $table->text('text_two')->nullable();
            //text three
            $table->string('title_three')->nullable();
            $table->text('text_three')->nullable();
            //text four
            $table->string('title_four')->nullable();
            $table->text('text_four')->nullable();
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
        Schema::dropIfExists('aboutuses');
    }
};