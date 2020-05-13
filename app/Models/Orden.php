<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'monto','estado'
    ];

    public function articulos()
    {
        return $this->hasMany('Models\Articulo');
    }

    public function cliente()
    {
        return $this->belongsTo('Models\Cliente');
    }
}
