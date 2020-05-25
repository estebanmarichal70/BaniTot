<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsersToOrdenes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::enableForeignKeyConstraints();
        Schema::table('ordenes', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //$table->foreignId('cliente_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::enableForeignKeyConstraints();
        Schema::table('ordenes', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->dropForeign('ordenes_user_id_foreign');
            //$table->foreignId('cliente_id')->nullable()->constrained();
        });
    }
}
