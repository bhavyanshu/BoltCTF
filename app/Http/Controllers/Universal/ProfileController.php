<?php

namespace BoltCTF\Http\Controllers\Universal;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Validator;
use Hash;
use DB;
use File;
use Response;
use Image;

use BoltCTF\Common\Utility;
use BoltCTF\Http\Controllers\Controller;
use BoltCTF\Model\User;
use BoltCTF\Model\Profile;

class ProfileController extends Controller
{
  /**
  * User model instance
  * @var User
  */
  protected $user;

  /**
  * For Guard
  *
  * @var Authenticator
  */
  protected $auth;

  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct(Guard $auth, User $user)
  {
    $this->user = $user;
    $this->auth = $auth;
  }

  protected function editform() {
    $userid = Auth::user()->id;
    $profile = Utility::getProfile(Auth::user());
    if (Auth::user()->role_id === 2) { //organizer
      return view('auth.users.organizer.profile.edit')->with('profile', $profile);
    }
    elseif (Auth::user()->role_id === 3) { //player
      return view('auth.users.player.profile.edit')->with('profile', $profile);
    }
  }

  protected function profpicupload(Request $request) {
    if($request->ajax()) {
      $file = $request->image;
      $input = array('image' => $file);
      $rules = array(
        'image' => 'mimes:jpeg,jpg,png|required|max:10000'
      );
      $validator = Validator::make($input, $rules);
      if ($validator->fails() )
      {
        return Response::json(['success' => false, 'errors' => $validator->getMessageBag()->toArray()]);
      }
      else {
        $img = Image::make($file)->resize(200, 250);
        $pathcheck = public_path().'/user/uploads/'.Auth::user()->username.'/';
        if(!File::exists($pathcheck)) {
          File::makeDirectory($pathcheck, 0775, true); //public access
        }
        $newfilename = str_random(6).$file->getClientOriginalName();
        $img->save($pathcheck.$newfilename);
        $profile = Utility::getProfile(Auth::user());
        $profile->profpic = $newfilename;
        $profile->save();
        return Response::json(['success' => true, 'file' => $newfilename]);
      }
    }
    else {
        return response('Invalid request.', 400);
    }
  }

  protected function saveProfileinfo(Request $request) {
    if($request->ajax()) {
      Utility::killXSS();
      $rules = array(
        'first_name' => 'required|max:255',
        'last_name' => 'required|max:255',
        'address' => 'required|string',
        'mobilenumber'=>'required|integer',
        'gender' => 'required|integer',
      );
      $validator = Validator::make($request->all(), $rules);
      $profile = Utility::getProfile(Auth::user());
      if($validator->passes()) {
        $profile->first_name = ucfirst($request->first_name);
        $profile->last_name = ucfirst($request->last_name);
        $profile->address = $request->address;
        $profile->gender = $request->gender;
        $profile->mobilenumber = $request->mobilenumber;
        $profile->save();
        $response = array(
            'status' => 'success',
            'msg' => 'Saved',
        );
        return Response::json($response);
      }
      else {
        $response = array(
            'status' => 'error',
            'msg' => $validator->getMessageBag()->toArray(),
        );
        return Response::json($response);
      }
    }
    else {
        return response('Invalid request.', 400);
    }
  }

  /**
   * GET Load view to show change password form.
   */
  protected function showPasschangeform() {
    $userid = Auth::user()->id;
    $profile = Utility::getProfile(Auth::user());
    if (Auth::user()->role_id === 2) {
      return view('auth.users.organizer.profile.password_change')->with('profile', $profile);
    }
    elseif (Auth::user()->role_id === 3) {
      return view('auth.users.player.profile.password_change')->with('profile', $profile);
    }
  }

  /**
   * POST Set new password post authentication
   *
   * @param  Request $request
   *
   * @return redirect
   */
  protected function passwordReset(Request $request) {
    $rules = array(
        'password' => 'required|confirmed',
        'password_confirmation' => 'required'
    );
    $validator = Validator::make($request->all(), $rules);

    if ($validator->passes())
    {
        $newpassword = $request->input('password');
        $passw = Hash::make($newpassword);
        $user = $request->user();
        $userid = $user->id;
        DB::table('users')
        ->where('id', $userid)
        ->update(array('password' => $passw, 'confirmed' => 1, 'blocked' => 0));
        return redirect()->route('dashboard')->with('message', trans('passwords.reset'));
    }
    return redirect()->route('password_change')
      ->withInput()
      ->withErrors($validator);
  }
}
