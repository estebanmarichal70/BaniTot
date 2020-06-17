<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'nombre', 'telefono', 'calle', 'info', 'ciudad', 'departamento', 'codigo', 'orden_id'
    ];

    public function orden()
    {
        return $this->belongsTo('App\Models\Orden','orden_id', 'id');
    }
}
