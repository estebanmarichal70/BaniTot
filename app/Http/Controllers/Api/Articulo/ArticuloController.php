<?php

namespace App\Http\Controllers\Api\Articulo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Articulo;
use Validator;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Articulo::all();
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
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required',
            'stock' => 'required',
            'imagen' => 'required',
            'marca' => 'required',
            'categoria'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $articulo = $request->all();
        $result = Articulo::create($articulo);

        return response()->json(['success'=>true, 'articulo'=>$result], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Articulo::findOrFail($id);
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
        $articulo = Articulo::find($id);

        if($request->nombre='' && $request->nombre=null)
            $articulo->nombre = $request->nombre;
        if($request->descripcion='' && $request->descripcion=null)
            $articulo->descripcion = $request->descripcion;
        if($request->precio=null)
            $articulo->precio = $request->precio;
        if($request->stock=null)
            $articulo->stock = $request->stock;
        if($request->imagen='' && $request->imagen=null)
            $articulo->imagen = $request->imagen;
        if($request->marca='' && $request->marca=null)
            $articulo->marca = $request->marca;
        if($request->categoria='' && $request->categoria=null)
            $articulo->categoria = $request->categoria;
        $articulo->save();
        return response()->json(['success'=>true,'articulo'=>$articulo], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Articulo::destroy($id))
            return response()->json(['success'=>true, 'message'=>'Articulo borrado exitosamente'], 201);
        return response()->json(['success'=>false, 'message'=>'Ocurrió un problema al eliminar'], 500);
    }
}
