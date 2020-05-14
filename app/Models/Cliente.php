<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends User
{
    /**
 * The attributes that are mass assignable.
 *
 * @var array
 */
    protected $fillable = [
        'id','calle','ciudad','departamento','cp'
    ];

    public function ordenes() {
        return $this->hasMany('Models\Orden','cliente_id', 'id');
    }

    public function feedbacks() {
        return $this->hasMany('Models\Feedback','cliente_id', 'id');
    }
}
