<?php

namespace App\Http\Controllers\Api\Direccion;

use App\Http\Controllers\Controller;
use App\Models\Direccion;
use Illuminate\Http\Request;
use Validator;

class DireccionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'telefono' => 'required',
            'calle' => 'required',
            'info' => 'required',
            'ciudad' => 'required',
            'departamento' => 'required',
            'codigo'=>'required',
            'orden_id'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $direccion = $request->all();
        $result = Direccion::create($direccion);

        return response()->json(['success'=>true, 'direccion'=>$result], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Direccion::findOrFail($id);
    }
}
