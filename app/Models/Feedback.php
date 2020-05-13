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
        'id','feedback','rating'
    ];

    public function articulo()
    {
        return $this->belongsTo('Models\Articulo');
    }

    public function cliente()
    {
        return $this->belongsTo('Models\Cliente');
    }
}
