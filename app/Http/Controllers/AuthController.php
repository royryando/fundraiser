<?php

namespace App\Http\Controllers;

use App\Mail\MailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
        return view('auth.register');
    }

    public function postRegister(Request $request) {
        $name = $request->input('name');
        $email = $request->input('username');
        $password = $request->input('password');
        $confirmPassword = $request->input('confirm_password');

        $check = User::where('email', $email)->first();
        if ($check) {
            $loginLink = route('auth.login');
            return redirect()
                ->back()
                ->with([
                    'message_type' => 'warning',
                    'message' => 'Email already registered, please <a href="'.$loginLink.'">login here</a>'
                ])
                ->withInput($request->except(['password', 'confirm_password']));
        }
        if ($password !== $confirmPassword) {
            return redirect()
                ->back()
                ->with([
                    'message_type' => 'warning',
                    'message' => "Confirmation password doesn't match"
                ])
                ->withInput($request->except(['password', 'confirm_password']));
        }
        $user = new User();
        $user->email = $email;
        $user->name = $name;
        $user->password = Hash::make($password);
        $user->is_admin = false;
        $user->save();

        Mail::to($user)
            ->send(new MailVerification(Crypt::encryptString($user->email)));

        return redirect()
            ->back()
            ->with([
                'message_type' => 'success',
                'message' => 'Registration success, please check your email'
            ])
            ->withInput($request->except(['password', 'confirm_password']));
    }

    public function logout() {
        Auth::logout();
        return redirect()
            ->route('index');
    }

}
