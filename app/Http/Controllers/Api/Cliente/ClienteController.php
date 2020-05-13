<?php

namespace App\Http\Controllers\Api\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Validator;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cliente::all();
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
            'calle' => 'required',
            'ciudad' => 'required',
            'departamento' => 'required',
            'cp' => 'required',
            'imagen' => 'required',
            'marca' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $cliente = $request->all();
        $result = Cliente::create($cliente);

        return response()->json(['success'=>true, 'cliente'=>$result], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Cliente::findOrFail($id);
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
        $cliente = Cliente::find($id);

        if($request->calle='' && $request->calle=null)
            $cliente->calle = $request->calle;
        if($request->ciudad='' && $request->ciudad=null)
            $cliente->ciudad = $request->ciudad;
        if($request->departamento='' && $request->departamento=null)
            $cliente->departamento = $request->departamento;
        if($request->cp='' && $request->cp=null)
            $cliente->cp = $request->cp;
        if($request->imagen='' && $request->imagen=null)
            $cliente->imagen = $request->imagen;
        if($request->marca='' && $request->marca=null)
            $cliente->marca = $request->marca;
        $cliente->save();
        return response()->json(['success'=>true,'cliente'=>$cliente], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Cliente::destroy($id))
            return response()->json(['success'=>true, 'message'=>'Cliente borrado exitosamente'], 201);
        return response()->json(['success'=>false, 'message'=>'Ocurrió un problema al eliminar'], 500);
    }
}
