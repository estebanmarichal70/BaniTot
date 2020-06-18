<?php

namespace App\Http\Controllers\Api\Carrito;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carrito;
use App\Models\Articulo;
use Validator;

class CarritoController extends Controller
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
            'carrito_id' => 'required',
            'articulo_id' => 'required',
            'cantidad' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $carr_art = $request->all();
        $carrito = Carrito::findOrFail($carr_art['carrito_id']);
        $articulo = Articulo::findOrFail($carr_art['articulo_id']);

        if($articulo->carrito()->where('carrito_id', $carrito['id'])->exists())
            $articulo->carrito()->updateExistingPivot($carrito['id'],['cantidad'=>$carr_art['cantidad']]);
        else
            $articulo->carrito()->attach($carrito,['cantidad'=>$carr_art['cantidad']]);

        return response()->json(['success'=>true, 'articulos_carrito'=>$carrito->articulos()->get()], 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carrito = Carrito::findOrFail($id);
        $carrito['articulos'] = $carrito->articulos()->get();
        return $carrito;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function detach(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'carrito_id' => 'required',
            'articulo_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $carr_art = $request->all();
        $carrito = Carrito::findOrFail($carr_art['carrito_id']);
        $articulo = Articulo::findOrFail($carr_art['articulo_id']);

        $articulo->carrito()->detach($carrito);

        return response()->json(['success'=>true, 'articulos_carrito'=>$carrito->articulos()->get()], 201);
    }
}
