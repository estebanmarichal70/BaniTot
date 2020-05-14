<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadoToOrdenes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::table('ordenes', function (Blueprint $table) {
            $table->enum('estado', ['RECIBIDO','EMBARCADO','CANCELADO','CONFIRMADO','PENDIENTE']);
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
        Schema::table('ordenes', function (Blueprint $table) {
            $table->enum('estado', ['RECIBIDO','EMBARCADO','CANCELADO','CONFIRMADO','PENDIENTE']);
        });
    }
}
