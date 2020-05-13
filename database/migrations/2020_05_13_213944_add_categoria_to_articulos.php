<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoriaToArticulos extends Migration
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
            $table->enum('categoria', ['GPU','CPU','RAM','CPU_COOLER','MOTHERBOARD','SSD','HDD','MONITOR','FUENTE','GABINETE','TECLADO','MOUSE','AURICULARES']);
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
            $table->enum('categoria', ['GPU','CPU','RAM','CPU_COOLER','MOTHERBOARD','SSD','HDD','MONITOR','FUENTE','GABINETE','TECLADO','MOUSE','AURICULARES']);
        });
    }
}
