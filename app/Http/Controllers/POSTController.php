<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Http\Shared\Constraints;
use App\Http\Shared\Errors;
use App\Http\Shared\Success;
use App\Http\Validator;
use App\Mail\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Nette\Utils\Random;
use Illuminate\Support\Str;

class POSTController extends Controller
{
    //
    public function signup()
    {
        /*
        request
            POST /signup
                    first_name,last_name,email,password,repeat_password
        */

        //validation
        #string checking
        $keys = [
            'first_name' => [
                'constraints' => Constraints::FIRST_NAME,
                'error_trigger' => Errors::INVALID_FIRST_NAME,
            ],
            'last_name' => [
                'constraints' => Constraints::LAST_NAME,
                'error_trigger' => Errors::INVALID_LAST_NAME,
            ],
            'email' => [
                'constraints' => Constraints::EMAIL,
                'error_trigger' => Errors::INVALID_EMAIL,
            ],
            'password' => [
                'constraints' => Constraints::PASSWORD,
                'error_trigger' => Errors::INVALID_PASSWORD
            ],
            'repeat_password' => [
                'constraints' => [
                    'required' => 'blah',
                    'identical_to' => 'password'
                ],
                'error_trigger' => Errors::INVALID_REPEAT_PASSWORD
            ]
        ];
        $error_session = [
            'email' => $_POST['email'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name']
        ];
        $validation_result = Validator::validate($keys, $_POST);
        if ($validation_result['success'] === false) {
            foreach ($validation_result['keys'] as $key) {
                $error_trigger = $keys[$key]['error_trigger'];
                $error_session[$error_trigger] = true;
            }
            return redirect('/')->with($error_session);
        }
        #database checking
        ## email must not be in db
        $email_count = User::where('email', $_POST['email'])->count();
        if ($email_count !== 0) {
            $error_session[Errors::EMAIL_EXISTS] = true;
            return redirect('/')->with($error_session);
        }


        //action
        $password_hash = Helpers::password_hash_with_pepper($_POST['password']);
        $user = new User();
        $user->first_name = $_POST['first_name'];
        $user->last_name = $_POST['last_name'];
        $user->email = $_POST['email'];
        $user->password = $password_hash;
        $action_result = $user->save();


        //success response
        if ($action_result) {
            return redirect('/')->with([
                Success::SIGNUP => true
            ]);
        } else {
            return redirect('/')->with([
                Errors::SERVER_ERROR => true
            ]);
        }
    }

    public function login()
    {
        /*
            request:
                POST /login
                    ?email=blah&password=blah
        */

        //validation
        # string check
        $keys = [
            'email' => [
                'constraints' => [
                    'required' => 'blah'
                ],
                'error_trigger' => Errors::WRONG_EMAIL_OR_PASSWORD
            ],
            'password' => [
                'constraints' => [
                    'required' => 'blah'
                ],
                'error_trigger' => Errors::WRONG_EMAIL_OR_PASSWORD
            ]
        ];
        $error_session = [];
        $validation_result = Validator::validate($keys, $_POST);
        if ($validation_result['success'] === false) {
            foreach ($validation_result['keys'] as $key) {
                $error_trigger = $keys[$key]['error_trigger'];
                $error_session[$error_trigger] = true;
            }
            return redirect('/')->with($error_session);
        }
        # db check
        ##email must exist in db
        $db_user = User::where('email', $_POST['email'])->get()->toArray();
        if (count($db_user) === 0) {
            return redirect('/')->with([Errors::WRONG_EMAIL_OR_PASSWORD => true]);
        }
        ##password must match from db
        $db_password = $db_user[0]['password'];
        $password_verified = Helpers::password_verify_with_pepper($_POST['password'], $db_password);
        if (!$password_verified) {
            return redirect('/')->with([Errors::WRONG_EMAIL_OR_PASSWORD => true]);
        }

        //action
        session([
            'user_logged_in' => true,
            'user_email' => $db_user[0]['email'],
            'user_first_name' => $db_user[0]['first_name'],
            'user_last_name' => $db_user[0]['last_name']
        ]);

        //response
        return redirect('/app');
    }

    public function changeProfileInfo()
    {
        /*
            request:
                POST /change-profile-info
                    ?first_name=blah&last_name=blah
        */

        //validation
        $keys = [
            'first_name' => [
                'constraints' => Constraints::FIRST_NAME,
                'error_trigger' => Errors::INVALID_FIRST_NAME
            ],
            'last_name' => [
                'constraints' => Constraints::LAST_NAME,
                'error_trigger' => Errors::INVALID_LAST_NAME
            ]
        ];
        $error_session = [];
        $validation_result = Validator::validate($keys, $_POST);
        if ($validation_result['success'] === false) {
            foreach ($validation_result['keys'] as $key) {
                $error_trigger = $keys[$key]['error_trigger'];
                $error_session[$error_trigger] = true;
            }
            return redirect('/app')->with($error_session);
        }


        //action
        $action_result = User::where('email', session('user_email'))
            ->update([
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name']
            ]);


        //response
        if ($action_result === 0) {
            return redirect('/app')->with([
                Errors::SERVER_ERROR => true
            ]);
        }

        session([
            'user_first_name' => $_POST['first_name'],
            'user_last_name' => $_POST['last_name']
        ]);
        return redirect('/app')->with([
            Success::PROFILE_SETTINGS => true
        ]);
    }


    public function changePassword()
    {
        /*
            request
                POST /change-password
                    ?current_password=blah&new_password=blah
                    &repeat_new_password=blah
        */

        //validation
        #string checking
        $keys = [
            'current_password' => [
                'constraints' => [
                    'required' => 'blah',
                    'min_length' => 8
                ],
                'error_trigger' => Errors::WRONG_PASSWORD
            ],
            'new_password' => [
                'constraints' => Constraints::PASSWORD,
                'error_trigger' => Errors::INVALID_PASSWORD
            ],
            'repeat_new_password' => [
                'constraints' => [
                    'required' => 'blah',
                    'identical_to' => 'new_password'
                ],
                'error_trigger' => Errors::INVALID_REPEAT_PASSWORD
            ]
        ];
        $error_session = [];
        $validation_result = Validator::validate($keys, $_POST);
        if ($validation_result['success'] === false) {
            foreach ($validation_result['keys'] as $key) {
                $error_trigger = $keys[$key]['error_trigger'];
                $error_session[$error_trigger] = true;
            }
            return redirect('/app')->with($error_session);
        }
        #db checking
        $db_password = User::where('email', session('user_email'))
            ->get()->toArray()[0]['password'];
        $verified = Helpers::password_verify_with_pepper(
            $_POST['current_password'],
            $db_password
        );
        if (!$verified) {
            return redirect('/app')->with([Errors::WRONG_PASSWORD => true]);
        }

        //action
        $new_password_hash = Helpers::password_hash_with_pepper(
            $_POST['new_password']
        );
        $action_result = User::where('email', session('user_email'))
            ->update([
                'password' => $new_password_hash
            ]);

        //reaponse
        if ($action_result === 0) {
            return redirect('/app')->with([
                Errors::SERVER_ERROR => true
            ]);
        }

        return redirect('/app')->with([
            Success::CHANGED_PASSWORD => true
        ]);
    }

    public function forgotPassword()
    {
        /*
            request
                POST /forgot-password
                    ?email=blah
        */

        //validation
        # string checking
        $keys = [
            'email' => [
                'constraints' => ['required' => 'blah'],
                'error_trigger' => Errors::EMAIL_NOT_FOUND
            ]
        ];
        $error_session = [];
        $validation_result = Validator::validate($keys, $_POST);
        if ($validation_result['success'] === false) {
            foreach ($validation_result['keys'] as $key) {
                $error_trigger = $keys[$key]['error_trigger'];
                $error_session[$error_trigger] = true;
            }
            return redirect('/')->with($error_session);
        }
        #db checking
        $count = User::where('email', $_POST['email'])->count();
        if ($count === 0) {
            return redirect('/')->with([Errors::EMAIL_NOT_FOUND => true]);
        }


        //action
        ## create a token and store it in db
        $u = (string) Str::uuid();
        $r = Random::generate(16);
        $token = "$r-$u";
        $action_result = User::where('email', $_POST['email'])
            ->update([
                'password_reset_token' => $token
            ]);
        if ($action_result === 0) {
            return redirect('/app')->with([
                Errors::SERVER_ERROR => true
            ]);
        }
        ## send an email with a url containing the token
        Mail::to($_POST['email'])->queue(new PasswordReset($token));

        //response
        return redirect('/')->with([
            Success::FORGOT_PASSWORD => true,
            'email' => $_POST['email']
        ]);
    }


    public function resetPassword()
    {
        /*
            request
                POST /reset-password
                ?password_reset_token=blah&new_password=blah
                &repeat_new_password=blah
        */

        //validation
        #string check
        $keys = [
            'password_reset_token' => [
                'constraints' => [
                    'required' => 'blah'
                ],
                'error_trigger' => Errors::INVALID_PASSWORD_RESET_TOKEN
            ],
            'new_password' => [
                'constraints' => Constraints::PASSWORD,
                'error_trigger' => Errors::INVALID_PASSWORD
            ],
            'repeat_new_password' => [
                'constraints' => [
                    'required' => 'blah',
                    'identical_to' => 'new_password'
                ],
                'error_trigger' => Errors::INVALID_REPEAT_PASSWORD
            ]
        ];
        $error_session = [];
        $validation_result = Validator::validate($keys, $_POST);
        if ($validation_result['success'] === false) {
            foreach ($validation_result['keys'] as $key) {
                $error_trigger = $keys[$key]['error_trigger'];
                $error_session[$error_trigger] = true;
            }
            return redirect("/reset-password/{$_POST['password_reset_token']}")
                ->with($error_session);
        }
        # db check
        $count = User::where('password_reset_token', $_POST['password_reset_token'])->count();
        if ($count === 0) {
            return redirect('/');
        }

        //action
        $new_password_hash = Helpers::password_hash_with_pepper($_POST['new_password']);
        $action_result = User::where('password_reset_token', $_POST['password_reset_token'])
            ->update([
                'password' => $new_password_hash,
                'password_reset_token' => null
            ]);

        // response
        if ($action_result === 0) {
            return redirect('/')->with([Errors::SERVER_ERROR => true]);
        }

        return redirect('/')->with([Success::CHANGED_PASSWORD => true]);
    }

    public function logout()
    {
        session()->flush();
        return redirect('/');
    }
}
