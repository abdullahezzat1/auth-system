<?php

namespace App\Http\Controllers;

use App\Models\User;

class GETController extends Controller
{
    public function home()
    {
        if (session('user_logged_in')) {
            return redirect('/app');
        }
        return view('home');
    }


    public function app()
    {
        if (session('user_logged_in')) {
            return view('app');
        } else {
            return redirect('/')->with(['error13' => true]);
        }
    }

    public function resetPassword($token)
    {
        /*
            request
                GET /reset-password/{token}
        */

        //validation
        $count = User::where('password_reset_token', $token)->count();
        if ($count === 0) {
            return redirect('/');
        }

        //action
        return view('password-reset', ['token' => $token]);
    }
}
