<?php

namespace BoltCTF\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Validator;
use Config;
use Hash;
use DB;
use File;
use Storage;

use BoltCTF\Http\Controllers\Controller;
use BoltCTF\Model\User;
use BoltCTF\Model\Profile;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

     /**
      * Create a new authentication controller instance.
      *
      * @return void
      */
      public function __construct(Guard $auth, User $user)
      {
          $this->user = $user;
          $this->auth = $auth;
      }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    protected function register(Request $request) {
      $rules = array(
        'username' => 'required|max:255|unique:users',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|confirmed|min:6',
        'password_confirmation'=>'required'
      );
      $messages = [
          'username.unique' => 'This username is already taken',
          'email.unique'  => 'An account already exists with this email address'
      ];
      $validator = Validator::make($request->all(), $rules, $messages);

      if ($validator->passes())
      {
        $confirmation_code = hash_hmac('sha256', str_random(40), str_random(10));

        $this->user->confirmation_code = $confirmation_code;
        $this->user->username = $request->username;
        $this->user->email = $request->email;
        $this->user->password = bcrypt($request->password);
        $this->user->role_id = 3;
        $this->user->blocked = 0;
        $this->api_token = Str::random();
        $this->user->save();

        $profile = new Profile;
        $profile->user()->associate($this->user);
        $profile->save();

        if ($this->auth->attempt($request->only('email', 'password'))) {
            if ($request->user()) {
              $user = $request->user();

              $mail_data = array(
                'confirmcode' => $confirmation_code
              );

              \Mail::send('emails.confirmation', $mail_data, function($message) use ($request) {
                  $message->to($this->user->email, ucfirst($request->username))
                      ->subject('Welcome '.ucfirst($request->username).', Verify your email address');
              });
            }
            return redirect()->route('dashboard')->with('message', trans('auth.verification_mail'));
        }
        else {
          return redirect('/')->with('message', trans('auth.verification_mail'));
        }
      }
      else {
        return redirect()->back()->withInput()
          ->withErrors($validator);
      }
    }

    protected function verifyEmail($confirmcode) {
      if(!$confirmcode) {
        return redirect()->route('/');
      }

      $user = User::whereConfirmationCode($confirmcode)->first();

      if($user->confirmed == 1) {
        $message = "You have already confirmed your email. You may continue using the services.";
        return redirect('/dashboard')->with('message',$message);
      }

      if(!$user) {
        $message = "Invalid confirmation code. Copy/Paste the url sent in your mail carefully. It's better to just click the link in mail and open in browser.";
        if(Auth::check()) {
          return redirect('/dashboard')->with('message',$message);
        }
        else {
          return redirect('/login')->with('message',$message);
        }
      }
      else {
        $user->confirmed = 1;
        $user->blocked = 0;
        $user->save();
        $pathcheck = public_path().'/user/uploads/'.$user->username;
        if(!File::exists($pathcheck)) {
          File::makeDirectory($pathcheck, 0775, true); //public access
        }
        Storage::makeDirectory('userfiles/uploads/'.$user->username); //auth access
        $message = 'You have successfully verified your account.';
        if(Auth::check()) {
          return redirect('user/profile/edit')->with('message','You have successfully verified your account. Go ahead and update your profile information.');
        }
        else {
          return redirect('/login')->with('message',$message);
        }
      }
    }
}
