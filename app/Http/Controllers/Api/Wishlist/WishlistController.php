<?php

namespace App\Http\Controllers\Api\Wishlist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Articulo;
use Validator;

class WishlistController extends Controller
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
            'wishlist_id' => 'required',
            'articulo_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $wish_art = $request->all();


        $wishlist = Wishlist::findOrFail($wish_art['wishlist_id']);
        $articulo = Articulo::findOrFail($wish_art['articulo_id']);

        $articulo->wishlist()->attach($wishlist);

        return response()->json(['success'=>true, 'articulos_wishlist'=>$wishlist->articulos()->get()], 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist['articulos'] = $wishlist->articulos()->get();
        return $wishlist;
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
            'wishlist_id' => 'required',
            'articulo_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $carr_art = $request->all();
        $wishlist = Wishlist::findOrFail($carr_art['wishlist_id']);
        $articulo = Articulo::findOrFail($carr_art['articulo_id']);

        $articulo->wishlist()->detach($wishlist);

        return response()->json(['success'=>true, 'articulos_wishlist'=>$wishlist->articulos()->get()], 201);
    }
}
