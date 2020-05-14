<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','feedback','rating', 'articulo_id', 'cliente_id'
    ];

    public function articulo()
    {
        return $this->belongsTo('Models\Articulo', 'articulo_id', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo('Models\Cliente','cliente_id', 'id');
    }
}
