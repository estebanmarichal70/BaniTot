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
    public function index(Request $request)
    {
        $marcas = Articulo::select('marca')->distinct()->get();

            /* Orden Asc */
            if ($request->query('order') == 'ASC') {

                /*Filtra por todos*/
                if ($request->query('search') != "" && $request->query('marca') != "" && $request->query('preciomax') != "") {

                    if ($request->query('preciomax') != "" && $request->query('preciomin') == 0) {
                        $articulo = Articulo::with('feedbacks')->where([['nombre', 'like', '%' . $request->query('search') . '%'], ['marca', '=', $request->query('marca')]])->where('precio', '<=', $request->query('preciomax'))->where('categoria', '=', $request->query('categoria'))->orderBy('precio')->paginate(9, ['*'], 'page', $request->query('page'));
                        $result = ['articulos' => $articulo, 'marcas' => $marcas];
                        return $result;
                    } else if ($request->query('preciomax') != 0 && $request->query('preciomin') != 0) {
                        $articulo = Articulo::with('feedbacks')->where([['nombre', 'like', '%' . $request->query('search') . '%'], ['marca', '=', $request->query('marca')]])->where('categoria', '=', $request->query('categoria'))->whereBetween('precio', [$request->query('preciomin'), $request->query('preciomax')])->orderBy('precio')->paginate(9, ['*'], 'page', $request->query('page'));
                        $result = ['articulos' => $articulo, 'marcas' => $marcas];
                        return $result;
                    } else if ($request->query('preciomax') == 0 && $request->query('preciomin') == 650) {
                        $articulo = Articulo::with('feedbacks')->where([['nombre', 'like', '%' . $request->query('search') . '%'], ['marca', '=', $request->query('marca')]])->where('precio', '>=', $request->query('preciomin'))->where('categoria', '=', $request->query('categoria'))->orderBy('precio')->paginate(9, ['*'], 'page', $request->query('page'));
                        $result = ['articulos' => $articulo, 'marcas' => $marcas];
                        return $result;
                    }
                }
                /*filtra todo menos marca*/
                if ($request->query('search') != "" && $request->query('marca') == "" && $request->query('preciomax') != "") {

                    if ($request->query('preciomax') != "" && $request->query('preciomin') == 0) {
                        $articulo = Articulo::with('feedbacks')->where([['nombre', 'like', '%' . $request->query('search') . '%'], ['precio', '<=', $request->query('preciomax')]])->where('categoria', '=', $request->query('categoria'))->orderBy('precio')->paginate(9, ['*'], 'page', $request->query('page'));
                        $result = ['articulos' => $articulo, 'marcas' => $marcas];
                        return $result;
                    } else if ($request->query('preciomax') != 0 && $request->query('preciomin') != 0) {
                        $articulo = Articulo::with('feedbacks')->where('nombre', 'like', '%' . $request->query('search') . '%')->where('categoria', '=', $request->query('categoria'))->whereBetween('precio', [$request->query('preciomin'), $request->query('preciomax')])->orderBy('precio')->paginate(9, ['*'], 'page', $request->query('page'));
                        $result = ['articulos' => $articulo, 'marcas' => $marcas];
                        return $result;
                    } else if ($request->query('preciomax') == 0 && $request->query('preciomin') == 650) {
                        $articulo = Articulo::with('feedbacks')->where([['nombre', 'like', '%' . $request->query('search') . '%'], ['precio', '>=', $request->query('preciomin')]])->where('categoria', '=', $request->query('categoria'))->orderBy('precio')->paginate(9, ['*'], 'page', $request->query('page'));
                        $result = ['articulos' => $articulo, 'marcas' => $marcas];
                        return $result;
                    }
                }
                /*Filtra todo menos nombre*/
                if($request->query('search') == "" && $request->query('marca') != "" && $request->query('preciomax') != "") {

                    if($request->query('preciomax') != "" && $request->query('preciomin') == 0 ){
                        $articulo = Articulo::with('feedbacks')->where('marca','=',$request->query('marca'))->where('categoria', '=', $request->query('categoria'))->where('precio','<=',$request->query('preciomax'))->orderBy('precio')->paginate(9,['*'],'page',$request->query('page'));
                        $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                        return $result;
                    }
                    else if($request->query('preciomax') != 0 && $request->query('preciomin') != 0){
                        $articulo = Articulo::with('feedbacks')->where('marca','=',$request->query('marca'))->where('categoria', '=', $request->query('categoria'))->whereBetween('precio',[$request->query('preciomin'),$request->query('preciomax')])->orderBy('precio')->paginate(9,['*'],'page',$request->query('page'));
                        $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                        return $result;
                    }
                    else if($request->query('preciomax') == 0 && $request->query('preciomin') == 650){
                        $articulo = Articulo::with('feedbacks')->where('marca','=',$request->query('marca'))->where('precio','>=',$request->query('preciomin'))->where('categoria', '=', $request->query('categoria'))->orderBy('precio')->paginate(9,['*'],'page',$request->query('page'));
                        $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                        return $result;
                    }
                }
                /* Filtra todo menos precio */
                if($request->query('search') != "" && $request->query('marca') != "" && $request->query('preciomax') == "") {

                    $articulo = Articulo::with('feedbacks')->where([['nombre','like','%'.$request->query('search').'%'],['marca','=',$request->query('marca')]])->where('categoria', '=', $request->query('categoria'))->orderBy('precio')->paginate(9,['*'],'page',$request->query('page'));
                    $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                    return $result;
                }
                /* Filtra solo por precio */
                if($request->query('search') == "" && $request->query('marca') == "" && $request->query('preciomax') != "") {

                    if($request->query('preciomax') != "" && $request->query('preciomin') == 0 ){
                        $articulo = Articulo::with('feedbacks')->where('precio','<=',$request->query('preciomax'))->where('categoria', '=', $request->query('categoria'))->orderBy('precio')->paginate(9,['*'],'page',$request->query('page'));
                        $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                        return $result;
                    }
                    else if($request->query('preciomax') != 0 && $request->query('preciomin') != 0){
                        $articulo = Articulo::with('feedbacks')->where('categoria', '=', $request->query('categoria'))->whereBetween('precio',[$request->query('preciomin'),$request->query('preciomax')])->orderBy('precio')->paginate(9,['*'],'page',$request->query('page'));
                        $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                        return $result;
                    }
                    else if($request->query('preciomax') == 0 && $request->query('preciomin') == 650){
                        $articulo = Articulo::with('feedbacks')->where('precio','>=',$request->query('preciomin'))->where('categoria', '=', $request->query('categoria'))->orderBy('precio')->paginate(9,['*'],'page',$request->query('page'));
                        $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                        return $result;
                    }
                }
                /* Filtra solo por nombre */
                if($request->query('search') != "" && $request->query('marca') == "" && $request->query('preciomax') == "") {
                    $articulo = Articulo::with('feedbacks')->where('nombre','like','%'.$request->query('search').'%')->where('categoria', '=', $request->query('categoria'))->orderBy('precio')->paginate(9,['*'],'page',$request->query('page'));
                    $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                    return $result;
                }
                /* Filtra solo por marca */
                if($request->query('search') == "" && $request->query('marca') != "" && $request->query('preciomax') == "") {
                    $articulo = Articulo::with('feedbacks')->where('marca','=',$request->query('marca'))->where('categoria', '=', $request->query('categoria'))->orderBy('precio')->paginate(9,['*'],'page',$request->query('page'));
                    $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                    return $result;
                }
                /* Filtra solo por orden */
                if($request->query('search') == "" && $request->query('marca') == "" && $request->query('preciomax') == "") {
                    $articulo = Articulo::with('feedbacks')->where('categoria', '=', $request->query('categoria'))->orderBy('precio')->paginate(9,['*'],'page',$request->query('page'));
                    $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                    return $result;
                }
            } /* Orden Desc */
            else if ($request->query('order') == 'DESC') {
                /*Filtra por todos*/
                if ($request->query('search') != "" && $request->query('marca') != "" && $request->query('preciomax') != "") {

                    if ($request->query('preciomax') != "" && $request->query('preciomin') == 0) {
                        $articulo = Articulo::with('feedbacks')->where([['nombre', 'like', '%' . $request->query('search') . '%'], ['marca', '=', $request->query('marca')]])->where('categoria', '=', $request->query('categoria'))->where('precio', '<=', $request->query('preciomax'))->orderByDesc('precio')->paginate(9, ['*'], 'page', $request->query('page'));
                        $result = ['articulos' => $articulo, 'marcas' => $marcas];
                        return $result;
                    } else if ($request->query('preciomax') != 0 && $request->query('preciomin') != 0) {
                        $articulo = Articulo::with('feedbacks')->where([['nombre', 'like', '%' . $request->query('search') . '%'], ['marca', '=', $request->query('marca')]])->where('categoria', '=', $request->query('categoria'))->whereBetween('precio', [$request->query('preciomin'), $request->query('preciomax')])->orderByDesc('precio')->paginate(9, ['*'], 'page', $request->query('page'));
                        $result = ['articulos' => $articulo, 'marcas' => $marcas];
                        return $result;
                    } else if ($request->query('preciomax') == 0 && $request->query('preciomin') == 650) {
                        $articulo = Articulo::with('feedbacks')->where([['nombre', 'like', '%' . $request->query('search') . '%'], ['marca', '=', $request->query('marca')]])->where('categoria', '=', $request->query('categoria'))->where('precio', '>=', $request->query('preciomin'))->orderByDesc('precio')->paginate(9, ['*'], 'page', $request->query('page'));
                        $result = ['articulos' => $articulo, 'marcas' => $marcas];
                        return $result;
                    }
                }
                /*Filtra todo menos marca*/
                if ($request->query('search') != "" && $request->query('marca') == "" && $request->query('preciomax') != "") {

                    if ($request->query('preciomax') != "" && $request->query('preciomin') == 0) {
                        $articulo = Articulo::with('feedbacks')->where([['nombre', 'like', '%' . $request->query('search') . '%'], ['precio', '<=', $request->query('preciomax')]])->where('categoria', '=', $request->query('categoria'))->orderByDesc('precio')->paginate(9, ['*'], 'page', $request->query('page'));
                        $result = ['articulos' => $articulo, 'marcas' => $marcas];
                        return $result;
                    } else if ($request->query('preciomax') != 0 && $request->query('preciomin') != 0) {
                        $articulo = Articulo::with('feedbacks')->where('nombre', 'like', '%' . $request->query('search') . '%')->where('categoria', '=', $request->query('categoria'))->whereBetween('precio', [$request->query('preciomin'), $request->query('preciomax')])->orderByDesc('precio')->paginate(9, ['*'], 'page', $request->query('page'));
                        $result = ['articulos' => $articulo, 'marcas' => $marcas];
                        return $result;
                    } else if ($request->query('preciomax') == 0 && $request->query('preciomin') == 650) {
                        $articulo = Articulo::with('feedbacks')->where([['nombre', 'like', '%' . $request->query('search') . '%'], ['precio', '>=', $request->query('preciomin')]])->where('categoria', '=', $request->query('categoria'))->orderByDesc('precio')->paginate(9, ['*'], 'page', $request->query('page'));
                        $result = ['articulos' => $articulo, 'marcas' => $marcas];
                        return $result;
                    }
                }
                /*Filtra todo menos nombre*/
                if($request->query('search') == "" && $request->query('marca') != "" && $request->query('preciomax') != "") {

                    if($request->query('preciomax') != "" && $request->query('preciomin') == 0 ){
                        $articulo = Articulo::with('feedbacks')->where('marca','=',$request->query('marca'))->where('precio','<=',$request->query('preciomax'))->where('categoria', '=', $request->query('categoria'))->orderByDesc('precio')->paginate(9,['*'],'page',$request->query('page'));
                        $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                        return $result;
                    }
                    else if($request->query('preciomax') != 0 && $request->query('preciomin') != 0){
                        $articulo = Articulo::with('feedbacks')->where('marca','=',$request->query('marca'))->whereBetween('precio',[$request->query('preciomin'),$request->query('preciomax')])->where('categoria', '=', $request->query('categoria'))->orderByDesc('precio')->paginate(9,['*'],'page',$request->query('page'));
                        $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                        return $result;
                    }
                    else if($request->query('preciomax') == 0 && $request->query('preciomin') == 650){
                        $articulo = Articulo::with('feedbacks')->where('marca','=',$request->query('marca'))->where('precio','>=',$request->query('preciomin'))->where('categoria', '=', $request->query('categoria'))->orderByDesc('precio')->paginate(9,['*'],'page',$request->query('page'));
                        $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                        return $result;
                    }
                }
                /* Filtra todo menos precio */
                if($request->query('search') != "" && $request->query('marca') != "" && $request->query('preciomax') == "") {

                    $articulo = Articulo::with('feedbacks')->where([['nombre','like','%'.$request->query('search').'%'],['marca','=',$request->query('marca')]])->where('categoria', '=', $request->query('categoria'))->orderByDesc('precio')->paginate(9,['*'],'page',$request->query('page'));
                    $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                    return $result;
                }
                /* Filtra solo por precio */
                if($request->query('search') == "" && $request->query('marca') == "" && $request->query('preciomax') != "") {

                    if($request->query('preciomax') != "" && $request->query('preciomin') == 0 ){
                        $articulo = Articulo::with('feedbacks')->where('precio','<=',$request->query('preciomax'))->where('categoria', '=', $request->query('categoria'))->orderByDesc('precio')->paginate(9,['*'],'page',$request->query('page'));
                        $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                        return $result;
                    }
                    else if($request->query('preciomax') != 0 && $request->query('preciomin') != 0){
                        $articulo = Articulo::with('feedbacks')->whereBetween('precio',[$request->query('preciomin'),$request->query('preciomax')])->where('categoria', '=', $request->query('categoria'))->orderByDesc('precio')->paginate(9,['*'],'page',$request->query('page'));
                        $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                        return $result;
                    }
                    else if($request->query('preciomax') == 0 && $request->query('preciomin') == 650){
                        $articulo = Articulo::with('feedbacks')->where('precio','>=',$request->query('preciomin'))->where('categoria', '=', $request->query('categoria'))->orderByDesc('precio')->paginate(9,['*'],'page',$request->query('page'));
                        $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                        return $result;
                    }
                }
                /* Filtra solo por nombre */
                if($request->query('search') != "" && $request->query('marca') == "" && $request->query('preciomax') == "") {
                    $articulo = Articulo::with('feedbacks')->where('nombre','like','%'.$request->query('search').'%')->where('categoria', '=', $request->query('categoria'))->orderByDesc('precio')->paginate(9,['*'],'page',$request->query('page'));
                    $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                    return $result;
                }
                /* Filtra solo por marca */
                if($request->query('search') == "" && $request->query('marca') != "" && $request->query('preciomax') == "") {
                    $articulo = Articulo::with('feedbacks')->where('marca','=',$request->query('marca'))->where('categoria', '=', $request->query('categoria'))->orderByDesc('precio')->paginate(9,['*'],'page',$request->query('page'));
                    $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                    return $result;
                }
                /* Filtra solo por orden */
                if($request->query('search') == "" && $request->query('marca') == "" && $request->query('preciomax') == "") {
                    $articulo = Articulo::with('feedbacks')->where('categoria', '=', $request->query('categoria'))->orderByDesc('precio')->paginate(9,['*'],'page',$request->query('page'));
                    $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                    return $result;
                }
            }
        /* Sin order */
        elseif ($request->query('order') != 'DESC' && $request->query('order') != 'ASC'){
            /*Filtra por todos*/
            if ($request->query('search') != "" && $request->query('marca') != "" && $request->query('preciomax') != "") {

                if ($request->query('preciomax') != "" && $request->query('preciomin') == 0) {
                    $articulo = Articulo::with('feedbacks')->where([['nombre', 'like', '%' . $request->query('search') . '%'], ['marca', '=', $request->query('marca')]])->where('categoria', '=', $request->query('categoria'))->where('precio', '<=', $request->query('preciomax'))->paginate(9, ['*'], 'page', $request->query('page'));
                    $result = ['articulos' => $articulo, 'marcas' => $marcas];
                    return $result;
                } else if ($request->query('preciomax') != 0 && $request->query('preciomin') != 0) {
                    $articulo = Articulo::with('feedbacks')->where([['nombre', 'like', '%' . $request->query('search') . '%'], ['marca', '=', $request->query('marca')]])->where('categoria', '=', $request->query('categoria'))->whereBetween('precio', [$request->query('preciomin'), $request->query('preciomax')])->paginate(9, ['*'], 'page', $request->query('page'));
                    $result = ['articulos' => $articulo, 'marcas' => $marcas];
                    return $result;
                } else if ($request->query('preciomax') == 0 && $request->query('preciomin') == 650) {
                    $articulo = Articulo::with('feedbacks')->where([['nombre', 'like', '%' . $request->query('search') . '%'], ['marca', '=', $request->query('marca')]])->where('categoria', '=', $request->query('categoria'))->where('precio', '>=', $request->query('preciomin'))->paginate(9, ['*'], 'page', $request->query('page'));
                    $result = ['articulos' => $articulo, 'marcas' => $marcas];
                    return $result;
                }
            }
            /*filtra todo menos marca*/
            if ($request->query('search') != "" && $request->query('marca') == "" && $request->query('preciomax') != "") {

                if ($request->query('preciomax') != "" && $request->query('preciomin') == 0) {
                    $articulo = Articulo::with('feedbacks')->where([['nombre', 'like', '%' . $request->query('search') . '%'], ['precio', '<=', $request->query('preciomax')]])->where('categoria', '=', $request->query('categoria'))->paginate(9, ['*'], 'page', $request->query('page'));
                    $result = ['articulos' => $articulo, 'marcas' => $marcas];
                    return $result;
                } else if ($request->query('preciomax') != 0 && $request->query('preciomin') != 0) {
                    $articulo = Articulo::with('feedbacks')->where('nombre', 'like', '%' . $request->query('search') . '%')->where('categoria', '=', $request->query('categoria'))->whereBetween('precio', [$request->query('preciomin'), $request->query('preciomax')])->paginate(9, ['*'], 'page', $request->query('page'));
                    $result = ['articulos' => $articulo, 'marcas' => $marcas];
                    return $result;
                } else if ($request->query('preciomax') == 0 && $request->query('preciomin') == 650) {
                    $articulo = Articulo::with('feedbacks')->where([['nombre', 'like', '%' . $request->query('search') . '%'], ['precio', '>=', $request->query('preciomin')]])->where('categoria', '=', $request->query('categoria'))->paginate(9, ['*'], 'page', $request->query('page'));
                    $result = ['articulos' => $articulo, 'marcas' => $marcas];
                    return $result;
                }
            }
            /*Filtra todo menos nombre*/
            if($request->query('search') == "" && $request->query('marca') != "" && $request->query('preciomax') != "") {

                if($request->query('preciomax') != "" && $request->query('preciomin') == 0 ){
                    $articulo = Articulo::with('feedbacks')->where('marca','=',$request->query('marca'))->where('precio','<=',$request->query('preciomax'))->where('categoria', '=', $request->query('categoria'))->paginate(9,['*'],'page',$request->query('page'));
                    $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                    return $result;
                }
                else if($request->query('preciomax') != 0 && $request->query('preciomin') != 0){
                    $articulo = Articulo::with('feedbacks')->where('marca','=',$request->query('marca'))->where('categoria', '=', $request->query('categoria'))->whereBetween('precio',[$request->query('preciomin'),$request->query('preciomax')])->paginate(9,['*'],'page',$request->query('page'));
                    $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                    return $result;
                }
                else if($request->query('preciomax') == 0 && $request->query('preciomin') == 650){
                    $articulo = Articulo::with('feedbacks')->where('marca','=',$request->query('marca'))->where('precio','>=',$request->query('preciomin'))->where('categoria', '=', $request->query('categoria'))->paginate(9,['*'],'page',$request->query('page'));
                    $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                    return $result;
                }
            }
            /* Filtra todo menos precio */
            if($request->query('search') != "" && $request->query('marca') != "" && $request->query('preciomax') == "") {

                $articulo = Articulo::with('feedbacks')->where([['nombre','like','%'.$request->query('search').'%'],['marca','=',$request->query('marca')]])->where('categoria', '=', $request->query('categoria'))->paginate(9,['*'],'page',$request->query('page'));
                $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                return $result;
            }
            /* Filtra solo por precio */
            if($request->query('search') == "" && $request->query('marca') == "" && $request->query('preciomax') != "") {

                if($request->query('preciomax') != "" && $request->query('preciomin') == 0 ){
                    $articulo = Articulo::with('feedbacks')->where('precio','<=',$request->query('preciomax'))->where('categoria', '=', $request->query('categoria'))->paginate(9,['*'],'page',$request->query('page'));
                    $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                    return $result;
                }
                else if($request->query('preciomax') != 0 && $request->query('preciomin') != 0){
                    $articulo = Articulo::with('feedbacks')->whereBetween('precio',[$request->query('preciomin'),$request->query('preciomax')])->where('categoria', '=', $request->query('categoria'))->paginate(9,['*'],'page',$request->query('page'));
                    $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                    return $result;
                }
                else if($request->query('preciomax') == 0 && $request->query('preciomin') == 650){
                    $articulo = Articulo::with('feedbacks')->where('precio','>=',$request->query('preciomin'))->where('categoria', '=', $request->query('categoria'))->paginate(9,['*'],'page',$request->query('page'));
                    $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                    return $result;
                }
            }
            /* Filtra solo por nombre */
            if($request->query('search') != "" && $request->query('marca') == "" && $request->query('preciomax') == "") {
                $articulo = Articulo::with('feedbacks')->where('nombre','like','%'.$request->query('search').'%')->where('categoria', '=', $request->query('categoria'))->paginate(9,['*'],'page',$request->query('page'));
                $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                return $result;
            }
            /* Filtra solo por marca */
            if($request->query('search') == "" && $request->query('marca') != "" && $request->query('preciomax') == "") {
                $articulo = Articulo::with('feedbacks')->where('marca','=',$request->query('marca'))->where('categoria', '=', $request->query('categoria'))->paginate(9,['*'],'page',$request->query('page'));
                $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
                return $result;
            }
        }
        $articulo = Articulo::with('feedbacks')->where('nombre','like','%'.$request->query('search').'%')->where('categoria', '=', $request->query('categoria'))->paginate(9,['*'],'page',$request->query('page'));
        $result = ['articulos'=>$articulo, 'marcas'=>$marcas];
        return $result;

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
        $articulo = Articulo::findOrFail($id);
        $articulo['feedbacks'] = $articulo->feedbacks()->get();

        return $articulo;
    }

    public function countRating($id)
    {
        $articulo = Articulo::findOrFail($id);
        $articulo = $articulo->feedbacks()->get()->count();

        return $articulo;
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
