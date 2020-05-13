<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeedbacksToArticulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::table('articulos', function (Blueprint $table) {
            $table->unsignedBigInteger('feedback_id');
            $table->foreign('feedback_id')->nullable()->references('id')->on('feedbacks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::enableForeignKeyConstraints();
        Schema::table('articulos', function (Blueprint $table) {
            $table->unsignedBigInteger('feedback_id');
            $table->foreign('feedback_id')->nullable()->references('id')->on('feedbacks');
        });
    }
}
