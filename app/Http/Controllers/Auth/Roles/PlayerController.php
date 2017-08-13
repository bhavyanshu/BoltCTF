<?php

namespace BoltCTF\Http\Controllers\Auth\Roles;

use DB;
use Validator;
use Hash;
use File;
use Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use BoltCTF\Http\Controllers\Controller;

use BoltCTF\Common\Utility;
use BoltCTF\Model\User;
use BoltCTF\Model\Profile;
use BoltCTF\Model\Event;
use BoltCTF\Model\Category;
use BoltCTF\Model\Challenge;
use BoltCTF\Model\UserPoint;
use BoltCTF\Model\IncorrectFlag;

class PlayerController extends Controller
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

  public function getEvent($eguid) {
    $profile = Utility::getProfile(Auth::user());
    $event = Event::with(['category.challenge.question.submittedflag' => function($q) {
      $q->where('sbmt_user_id', Auth::user()->id);
    }])->where('ref_guid', $eguid)->firstOrFail();

    if(Utility::hasEventExpired($event) == true) {
      return View('auth.users.player.events.expired')->with('event', $event)->with('profile', $profile);
    }

    $total_ques = 0;
    $answered_ques = 0;
    foreach($event->category as $cat) {
        foreach($cat->challenge as $cha) {
          $total_ques += count($cha->question);
          foreach($cha->question as $que) {
            if($que->submittedflag) {
              $answered_ques += 1;
            }
          }
        }
    }

    $userpoint = UserPoint::where('upt_ev_id', $event->event_id)->where('upt_user_id', Auth::user()->id)->first();
    $points = $userpoint ? $userpoint->upt_points : '0';

    $incorrect_attempts = IncorrectFlag::where('if_user_id', Auth::user()->id)->where('if_ev_id', $event->event_id)->count();

    $stats = (Object) array(
      'total_ques' => $total_ques,
      'answered_ques' => $answered_ques,
      'points' => $points,
      'incorrect_attempts' => $incorrect_attempts
    );


    return View('auth.users.player.events.stadium')->with('event', $event)->with('stats', $stats)->with('profile', $profile);
  }

  public function getChallenge(Request $request, $eguid, $chaguid) {
    $profile = Utility::getProfile(Auth::user());
    $event = Event::with('category.challenge')->where('ref_guid', $eguid)->firstOrFail();

    if(Utility::hasEventExpired($event) == true) {
      return View('auth.users.player.events.expired')->with('event', $event)->with('profile', $profile);
    }

    $challenge = Challenge::where('ref_guid', '=', $chaguid)->firstOrFail();
    return View('auth.users.player.events.challenges')->with('event', $event)->with('challenge', $challenge)->with('profile', $profile);
  }

  public function getLeaderboard($eguid) {
    $event = Event::with('category.challenge')->where('ref_guid', $eguid)->firstOrFail();
    $profile = Utility::getProfile(Auth::user());

    return View('auth.users.player.events.leaderboard')->with('event', $event)->with('profile', $profile);
  }
}
