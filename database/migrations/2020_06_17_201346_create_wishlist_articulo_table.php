<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlistArticuloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wishlist_articulo', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wishlist_id')->unsigned();
            $table->bigInteger('articulo_id')->unsigned();

            $table->foreign('wishlist_id')->references('id')->on('wishlist')->onDelete('cascade');
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
        Schema::dropIfExists('wishlist_articulo');
    }
}
