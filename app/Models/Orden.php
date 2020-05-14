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
        'id', 'monto','estado','cliente_id'
    ];

    public function articulos()
    {
        return $this->belongsToMany('App\Models\Articulo', 'orden_articulo', 'orden_id', 'articulo_id');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'cliente_id', 'id');
    }
}
