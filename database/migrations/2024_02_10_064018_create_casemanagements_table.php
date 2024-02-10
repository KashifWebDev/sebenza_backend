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
        Schema::create('casemanagements', function (Blueprint $table) {
            $table->id();
            $table->string('caseID');
            $table->integer('customer_id');
            $table->string('customer_name');
            $table->string('case_title')->nullable();
            $table->text('description')->nullable();
            $table->longText('phases')->nullable();
            $table->float('budget',10,2)->default(0);
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
            $table->float('progress')->default(0);
            $table->integer('assign_to');
            $table->integer('user_id');
            $table->string('membership_code');
            $table->boolean('customer_can_view');
            $table->boolean('customer_can_comment');
            $table->string('priority');
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
        Schema::dropIfExists('casemanagements');
    }
};
