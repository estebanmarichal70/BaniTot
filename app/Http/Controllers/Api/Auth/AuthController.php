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

        return response()->json(['success' => true, 'message' => "Se ha enviado un mail de confirmacion."], 201);
    }

    public function userDetail()
    {
        $user = Auth::user();

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
