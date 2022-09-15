<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function signup(SignUpRequest $request)
    {
        $validated = $request->validated();

        $user = new User();
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect('/app');
    }

    public function viewVerifyEmail()
    {
        return view('auth.verifyEmail');
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect('/app');
    }

    public function resendVerification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with(['success' => 'Verification link sent to your email. Please check your email.']);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $authenticated = Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ]);

        if ($authenticated) {
            $request->session()->regenerate();
            return redirect('/app');
        } else {
            return redirect('/')->withErrors(['Wrong email or password']);
        }
    }


    public function viewAccount()
    {
        $user = Auth::user();
        return view('app', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function viewResetPassword($token)
    {
        return view('password-reset', ['token' => $token]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();

        $current_password_correct = Hash::check($validated['current_password'], Auth::user()->password);

        if (!$current_password_correct) {
            return redirect('/account/logout');
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return back()->with(['success' => 'Changed password successfully']);
    }

    public function changeInfo(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'min:1', 'max:50'],
            'last_name' => ['required', 'min:1', 'max:50']
        ]);

        $user = Auth::user();
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->save();

        return back()->with(['success' => 'Changed info successfully.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
