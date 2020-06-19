<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Carrito;
use App\Models\Role;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Validator;
use Hash;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
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
        $u = User::create($user);

        $role = Role::where("nombre", "=", "CLIENTE")->first();

        if ($role == null) {
            $role = Role::create(['nombre' => 'CLIENTE']);
            $u->roles()->attach($role);
        } else {
            $u->roles()->attach($role);
        }

        Carrito::create(["user_id"=>$u["id"]]);
        Wishlist::create(["user_id"=>$u["id"]]);

        $this->sendEmailReminder($u);

        return response()->json(['success' => true, 'message' => "Se ha enviado un mail de confirmación."], 201);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->all();

        if(isset($data['name'])){
            $user->update(['name' => $data['name']]);
        }
        if(isset($data['fecha_nac'])){
            $user->update(['fecha_nac' => $data['fecha_nac']]);
        }
        if(isset($data['telefono'])){
            $user->update(['telefono' => $data['telefono']]);
        }
        if(isset($data['departamento'])){
            $user->update(['departamento' => $data['departamento']]);
        }
        if(isset($data['cp'])){
            $user->update(['cp'=> $data['cp']]);
        }
        if(isset($data['ciudad'])){
            $user->update(['ciudad'=> $data['ciudad']]);
        }
        if(isset($data['calle'])){
            $user->update(['calle'=> $data->input('calle')]);
        }
        if(isset($data['passwordNuevo'])){
            if($data['passwordViejo'] == $user['password']){
                $user->update(['password'=> $data['passwordNuevo']]);
            }
        }
        return response()->json(['success' => true, 'message' => "Se ha actualizado la informacion del usuario."], 201);
    }

    public function userDetail()
    {
        $user = Auth::user();
        $user['carrito'] = $user->carrito()->get();
        $user['wishlist'] = $user->wishlist()->get();

        return response()->json(['user' => $user], 200);

    }


    /**
     * Send an e-mail to the user.
     *
     * @param  User  $user
     */
    public function sendEmailReminder($user)
    {
        Mail::send('emails.confirm_account', ['user' => $user], function ($m) use ($user) {
            $m->from('no-responder@banitot.uy', 'Banitot PCs & Componentes');

            $m->to($user->email, $user->name)->subject('Confirma tu cuenta!');
        });
    }
}
