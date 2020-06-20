<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Carrito;
use App\Models\Role;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Hash;
use App\Notifications\SignupActivate;
use App\Models\User;
use Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $credentials = request(['email', 'password']);
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;

        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $data['user'] = $user;
            $data['user']['roles'] = $user->roles()->get();
            $data['user']['carrito'] = $user->carrito()->get();
            $data['user']['wishlist'] = $user->wishlist()->get();
            $data['token'] = $user->createToken('BanitotApp')->accessToken;
            return response()->json($data, 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }


        $user = $request->all();
        $user['password'] = Hash::make($user['password']);
        $user['activation_token'] = Str::random(60);
        $u = User::create($user);

        $role = Role::where("nombre", "=", "CLIENTE")->first();

        if ($role == null) {
            $role = Role::create(['nombre' => 'CLIENTE']);
            $u->roles()->attach($role);
        } else {
            $u->roles()->attach($role);
        }

        Carrito::create(["user_id" => $u["id"]]);
        Wishlist::create(["user_id" => $u["id"]]);

        $u->notify(new SignupActivate($user));

        return response()->json(['success' => true, 'message' => "Se ha enviado un mail de confirmación."], 201);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->all();

        if (isset($data['name'])) {
            $user->update(['name' => $data['name']]);
        }
        if (isset($data['fecha_nac'])) {
            $user->update(['fecha_nac' => $data['fecha_nac']]);
        }
        if (isset($data['telefono'])) {
            $user->update(['telefono' => $data['telefono']]);
        }
        if (isset($data['departamento'])) {
            $user->update(['departamento' => $data['departamento']]);
        }
        if (isset($data['cp'])) {
            $user->update(['cp' => $data['cp']]);
        }
        if (isset($data['ciudad'])) {
            $user->update(['ciudad' => $data['ciudad']]);
        }
        if(isset($data['calle'])){
            $user->update(['calle'=> $data['calle']]);
        }
        if(isset($data['passwordNuevo'])){

            if(Hash::check($data['passwordViejo'],$user['password'])){
                $data['passwordNuevo'] = Hash::make($data['passwordNuevo']);
                $user->update(['password'=> $data['passwordNuevo']]);
            }
            else{
                return response()->json(['error' => "La contraseña introducida es incorrecta"],401);
            }
        }
        return response()->json(['success' => true, 'message' => "Se ha actualizado la informacion del usuario."], 201);
    }

    public function userDetail()
    {
        $user = Auth::user();
        $user['ordenes'] = $user->ordenes()->get();
        $user['carrito'] = $user->carrito()->get();
        $user['wishlist'] = $user->wishlist()->get();

        return response()->json(['user' => $user], 200);

    }

    public function activateAccount($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return response()->json([
                'success' => 'false',
                'message' => 'El usuario no existe o ya se encuentra activo.'
            ], 404);
        }
        $user->active = true;
        $user->activation_token = '';
        $user->email_verified_at = Carbon::now()->toDateTimeString();
        $user->save();
        return Redirect::to(env('APP_CONFIRM_ACCOUNT_REDIRECT', 'http://localhost:8080/cuenta-confirmada'));
    }
}
