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
        return $this->hasMany('Models\Feedback', 'articulo_id', 'id');
    }
}
