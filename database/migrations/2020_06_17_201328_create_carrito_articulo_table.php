<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarritoArticuloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrito_articulo', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('carrito_id')->unsigned();
            $table->bigInteger('articulo_id')->unsigned();
            $table->bigInteger('cantidad');

            $table->foreign('carrito_id')->references('id')->on('carrito')->onDelete('cascade');
            $table->foreign('articulo_id')->references('id')->on('articulos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carrito_articulo');
    }
}
