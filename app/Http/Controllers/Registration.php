<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect,Response;

use App\User;
use Session;
use Validator;

class Registration extends Controller
{
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'nume' => 'required|string|max:255',
            'unitate_de_invatamant' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6|max:255',
            'password_confirm' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()]);
        }

        if (count(User::where("email", "=", $request->email)->get()) != 0) {

            $email_error = (object) [
                'error' => ["Email-ul deja exista"]
            ];

            return Response::json(['errors' => $email_error]);

        }

        if ($request->password != $request->password_confirm) {

            $password_error = (object) [
                'error' => ["Parolele sunt diferte"]
            ];

            return Response::json(['errors' => $password_error]);
        }

        $user = new User;
        $user->nume = $request->nume;
        $user->unitate_de_invatamant = $request->unitate_de_invatamant;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        Session::put("user", $user);

        return Response::json(['success' => '1']);

    }
}
