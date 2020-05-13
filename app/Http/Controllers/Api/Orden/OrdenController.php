<?php

namespace App\Http\Controllers\Api\Orden;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class OrdenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Orden::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'articulos' => 'required',
            'estado'=> 'required',
            'monto' => 'required',
            'cliente'=> 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $orden = $request->all();
        $result = Orden::create($orden);

        return response()->json(['success'=>true, 'orden'=>$result], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Orden::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $orden = Orden::find($id);

        if($request->articulos='' && $request->articulos=null)
            $orden->articulos = $request->articulos;
        if($request->estado='' && $request->estado=null)
            $orden->estado = $request->estado;
        if($request->monto='' && $request->monto=null)
            $orden->monto = $request->monto;
        $orden->save();
        return response()->json(['success'=>true,'orden'=>$orden], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Orden::destroy($id))
            return response()->json(['success'=>true, 'message'=>'Orden borrada exitosamente'], 201);
        return response()->json(['success'=>false, 'message'=>'Ocurrió un problema al eliminar'], 500);
    }
}
