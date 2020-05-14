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
        return $this->hasMany('Models\Articulo', 'orden_id', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo('Models\Cliente', 'cliente_id', 'id');
    }
}
