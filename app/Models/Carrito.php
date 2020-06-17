<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
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
        return $this->belongsToMany('App\Models\Articulo', 'articulo_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo('App\Models\User','user_id', 'id');
    }
}
