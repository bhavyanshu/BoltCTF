<?php

namespace BoltCTF\Http\Controllers\Universal;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use BoltCTF\Http\Controllers\Controller;

use BoltCTF\Model\User;
use BoltCTF\Model\ChallengeFile;

use Storage;

class FileController extends Controller
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

  public function fileDownload($file_token) {
    try {
      $cf = ChallengeFile::where('f_token', $file_token)->firstOrFail();
      $storagePath = public_path('storage/challenge/'. $cf->user->username . '/');
      return response()->download($storagePath . $cf->f_name);
    }
    catch (Exception $e){
      return abort(404);
    }
  }
}
