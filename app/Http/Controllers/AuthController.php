<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login() {
        if (Auth::check()) {
            return redirect()
                ->route('index');
        }
        return view('auth.login');
    }

    public function postLogin(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)
            ->first();

        if (!$user) {
            // wrong email or password
            return redirect()
                ->back()
                ->with([
                    'message_type' => 'warning',
                    'message' => 'Wrong email or password, please try again'
                ]);
        }
        if (!Hash::check($password, $user->password)) {
            // wrong email or password
            return redirect()
                ->back()
                ->with([
                    'message_type' => 'warning',
                    'message' => 'Wrong email or password, please try again'
                ]);
        } else if ($user->email_verified_at == null) {
            // please activate your account
            return redirect()
                ->back()
                ->with([
                    'message_type' => 'info',
                    'message' => 'Please verify your email'
                ]);
        }

        Auth::login($user);
        return redirect()
            ->route('index'); // TODO: redirect to home page
    }

    public function register() {
        //
    }

    public function postRegister() {
        //
    }

    public function logout() {
        Auth::logout();
        return redirect()
            ->route('index');
    }

}
