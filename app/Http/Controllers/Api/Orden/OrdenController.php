<?php

namespace App\Http\Controllers\Api\Orden;

use App\Http\Controllers\Controller;
use App\Models\Direccion;
use App\Models\Orden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;
use App\Models\Articulo;


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
        /*$validator = Validator::make($request->all(), [
            'estado'=> 'required',
            'monto' => 'required',
            'user_id'=> 'required',
            'articulos'=>'required',
            'direccion'=>'required'

        ]);*/



        /*if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $orden = $request->all();
        $result = Orden::create($orden);
        foreach ($orden['articulos'] as $articulo_id ){
            $articulo = Articulo::findOrFail($articulo_id['id']);
            $result->articulos()->attach($articulo,['cantidad'=>$articulo_id['cantidad']]);
        }

        $direccion = $request->input('direccion');
        $direccion['orden_id'] = $result['id'];
        $direccion = Direccion::create($direccion);
        $result['direccion'] = $direccion;

        return response()->json(['success'=>true, 'orden'=>$result], 201);*/
        return null;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orden = Orden::findOrFail($id);
        $orden['articulos'] = $orden->articulos()->get();
        $orden['direccion'] = $orden->direccion()->get();
        return $orden;
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
