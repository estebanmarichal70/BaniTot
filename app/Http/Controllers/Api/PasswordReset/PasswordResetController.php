<?php

namespace App\Http\Controllers\Api\PasswordReset;

use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Validator;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user)
            return response()->json(['message' => 'Usuario no existente'], 404);

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60)
            ]
        );

        if ($user && $passwordReset) $user->notify(new PasswordResetRequest($passwordReset->token));

        return response()->json([
            'success' => true,
            'message' => 'Se ha enviado un mail con los pasos a seguir.'
        ]);
    }


    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset)
            return response()->json([
                'success' => false,
                'message' => 'No existe la solicitud de cambio de contraseña ingresada.'
            ], 404);

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'success' => false,
                'message' => 'La solicitud de cambio es invalida.'
            ], 404);
        }
        return
            Redirect::to('http://localhost:8080/restablecer-contraseña?token=' . $passwordReset->token . '&email=' . $passwordReset->email);
    }

    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);

        $user = User::where('email', $passwordReset->email)->first();

        if (!$user)
            return response()->json([
                'success' => false,
                'message' => 'No se encontró un usuario con el mail solicitado.'], 404);

        $user->password = Hash::make($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess());
        return
            response()->json([
                'success' => true,
                'message' => 'El cambio se ha efectuado con exito.'], 200);
    }
}
