<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'nombre', 'descripcion','precio','stock','categoria','imagen','marca','categoria'
    ];

    public function feedbacks() {
        return $this->hasMany('App\Models\Feedback', 'articulo_id', 'id');
    }

    public function ordenes(){
        return $this->belongsToMany('App\Models\Orden', 'orden_articulo', 'articulo_id','orden_id');
    }

    public function carritos(){
        return $this->belongsToMany('App\Models\Carrito', 'carrito-articulo', 'articulo_id','carrito_id')->withPivot('cantidad');;
    }

    public function wishlists(){
        return $this->belongsToMany('App\Models\Wishlist', 'wishlist-articulo_', 'articulo_id','wishlist_id');
    }
}
