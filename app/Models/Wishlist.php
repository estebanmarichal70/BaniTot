<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = "wishlist";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',  'user_id'
    ];

    public function articulos()
    {
        return $this->belongsToMany('App\Models\Articulo', 'wishlist_articulo', 'articulo_id','wishlist_id');
    }

    public function usuario()
    {
        return $this->belongsTo('App\Models\User','user_id', 'id');
    }
}
