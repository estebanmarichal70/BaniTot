<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table = "ordenes";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'monto','estado', 'user_id'
    ];

    public function articulos()
    {
        return $this->belongsToMany('App\Models\Articulo', 'orden_articulo', 'orden_id', 'articulo_id')->withPivot('cantidad');
    }

    public function direccion()
    {
        return $this->hasOne('App\Models\Direccion', 'direccion_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
