<?php

namespace App\Http\Controllers\Api\Orden;

use App\Http\Controllers\Controller;
use App\Models\Direccion;
use App\Models\Orden;
use App\Models\User;
use App\Notifications\OrderProcessing;
use Illuminate\Http\Request;
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required',
            'monto' => 'required',
            'user_id' => 'required',
            'articulos' => 'required',
            'direccion' => 'required'

        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $orden = $request->all();
        $result = Orden::create($orden);

        foreach ($orden['articulos'] as $articulo_id) {
            $articulo = Articulo::findOrFail($articulo_id['id']);
            $stock = $articulo->stock - $articulo_id['cantidad'];
            $articulo->update(["stock" => $stock]);
            $result->articulos()->attach($articulo, ['cantidad' => $articulo_id['cantidad']]);
        }

        $direccion = $request->input('direccion');
        $direccion['orden_id'] = $result['id'];
        $direccion = Direccion::create($direccion);
        $result['direccion'] = $direccion;

        $user = User::find($request['user_id']);
        $user->notify(new OrderProcessing($result));

        return response()->json(['success' => true, 'orden' => $result], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orden = Orden::findOrFail($id);
        $orden['articulos'] = $orden->articulos()->with('feedbacks')->get();
        $orden['direccion'] = $orden->direccion()->get();
        return $orden;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $orden = Orden::findOrFail($id);
        $articulos = $orden->articulos()->get();

        foreach ($articulos as $articulo_id ){
            $articulo = Articulo::findOrFail($articulo_id['id']);
            $articulo->update(['stock' => $articulo['stock'] + $articulo_id['pivot']['cantidad']]);
        }
        $orden->estado = "CANCELADO";
        $orden->save();
        return response()->json(['success' => true, 'orden' => $orden], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Orden::destroy($id))
            return response()->json(['success' => true, 'message' => 'Orden borrada exitosamente'], 201);
        return response()->json(['success' => false, 'message' => 'Ocurrió un problema al eliminar'], 500);
    }
}
