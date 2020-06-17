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

        $wish_art = $request->all();


        $carrito = Carrito::findOrFail($wish_art['carrito_id']);
        $articulo = Articulo::findOrFail($wish_art['articulo_id']);

        $articulo->carrito()->attach($carrito,['cantidad'=>$wish_art['cantidad']]);

        return response()->json(['success'=>true, 'carrito_articulo'=>$articulo], 201);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
