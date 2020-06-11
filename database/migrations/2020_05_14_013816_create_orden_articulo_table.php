<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenArticuloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_articulo', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('orden_id')->unsigned();
            $table->bigInteger('articulo_id')->unsigned();
            $table->bigInteger('cantidad');

            $table->foreign('orden_id')->references('id')->on('ordenes')->onDelete('cascade');
            $table->foreign('articulo_id')->references('id')->on('articulos')->onDelete('cascade');
            //$table->foreignId('orden_id')->nullable()->constrained('ordenes');
            //$table->foreignId('articulo_id')->nullable()->constrained();
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
        Schema::dropIfExists('orden_articulo');
    }
}
