<?php

namespace BoltCTF\Http\Controllers\Home;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use BoltCTF\Http\Requests;
use BoltCTF\Http\Controllers\Controller;
use BoltCTF\Common\Utility;

use BoltCTF\Model\User;
use BoltCTF\Model\Profile;

/**
* Controller for dashboard related features by role
*/
class HomeController extends Controller
{
  /**
  * User model instance
  *
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
  * @param Guard $auth
  * @param User $user
  *
  * @return void
  */
  public function __construct(Guard $auth, User $user)
  {
    $this->user = $user;
    $this->auth = $auth;
  }

  protected function getSiteLandingPage() {
    return view('non-auth.welcome');
  }

  /**
  * @method index
  * GET View for dashboard based on role
  * @return Response
  */
  public function index()
  {
    $loggedinUser = Auth::user();
    $profile = Utility::getProfile($loggedinUser);

    if(Auth::user()->role_id === 2) { //organizer
      return view('auth.users.organizer.dashboard')
      ->with('profile', $profile);
    }
    elseif(Auth::user()->role_id === 3) { //player
      return view('auth.users.player.dashboard')
      ->with('profile',$profile);
    }
  }
}
