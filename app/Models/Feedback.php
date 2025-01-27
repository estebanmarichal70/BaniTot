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
        'id','feedback','rating', 'articulo_id', 'user_id'
    ];

    public function articulo()
    {
        return $this->belongsTo('App\Models\Articulo', 'articulo_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo('App\Models\User','user_id', 'id');
    }
}
