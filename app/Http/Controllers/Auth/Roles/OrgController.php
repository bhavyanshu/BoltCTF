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

class OrgController extends Controller
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

  /**
   * GET View to display form to register event.
   *
   * @return Response
   */
  public function registerEvent() {
    $profile = Utility::getProfile(Auth::user());
    return View('auth.users.organizer.events.event_form')->with('event',null)->with('profile', $profile);
  }

  /**
   * GET View to display form to update event.
   *
   * @return Response
   */
  public function editEvent($eguid) {
    $event = Event::where('createdBy_id', '=', Auth::user()->id)->where('ref_guid', '=' ,$eguid)->firstOrFail();
    $profile = Utility::getProfile(Auth::user());
    return View('auth.users.organizer.events.event_form')->with('event', $event)->with('profile', $profile);
  }

  /**
   * POST Save event related information
   *
   * @param Request $request
   *
   * @return Response
   */
  public function saveEvent(Request $request) {
    if(is_null($request->ref_guid)) { // register new
      $rules = array(
        'name' => 'required|max:255',
        'start_time' => 'required|max:255',
        'end_time' => 'required|max:255',
        'description' => 'required'
      );
      $validator = Validator::make($request->all(), $rules);
      if ($validator->passes())
      {
        $c = new Event;
        $c->user()->associate(Auth::user());
        $c->name = ucfirst($request->name);
        $c->start_time = $request->start_time;
        $c->ref_guid = str_random(20);
        $c->end_time = $request->end_time;
        $c->description = $request->description;
        $c->save();
        return redirect()->route('dashboard')->with('message','A new event has been registered.');
      }
      else {
        return redirect()->back()->withInput()
          ->withErrors($validator);
      }
    }
    else { //edit product
      $rules = array(
        'ref_guid' => 'required',
        'name' => 'required|max:255',
        'start_time' => 'required|max:255',
        'end_time' => 'required|max:255',
        'description' => 'required'
      );
      $validator = Validator::make($request->all(), $rules);
      if ($validator->passes())
      {
        $c = Event::where('createdBy_id','=',Auth::user()->id)->where('ref_guid','=',$request->ref_guid)->firstOrFail();
        $c->user()->associate(Auth::user());
        $c->name = ucfirst($request->name);
        $c->start_time = $request->start_time;
        $c->end_time = $request->end_time;
        $c->description = $request->description;
        $c->save();
        return redirect()->route('dashboard')->with('message','The event information has been updated.');
      }
      else {
        return redirect()->back()->withInput()
          ->withErrors($validator);
      }
    }
  }

  /**
   * GET View to display form for category editor.
   *
   * @return Response
   */
  public function editCategory($eguid) {
    $event = Event::where('createdBy_id', '=', Auth::user()->id)->where('ref_guid', '=' ,$eguid)->firstOrFail();
    $profile = Utility::getProfile(Auth::user());
    return View('auth.users.organizer.events.edit_category')->with('event', $event)->with('profile', $profile);
  }

  /**
   * GET View to display form for challenge editor.
   *
   * @return Response
   */
  public function editChallenge($chaguid) {
    $challenge = Challenge::where('ref_guid', '=' , $chaguid)->firstOrFail();
    $event = $challenge->category->event;
    if($event->createdBy_id !== Auth::user()->id) { //check if authorized to edit
      abort(404);
    }
    $profile = Utility::getProfile(Auth::user());
    return View('auth.users.organizer.events.edit_challenge')->with('challenge', $challenge)->with('profile', $profile);
  }

  public function getUserManager() {
    $profile = Utility::getProfile(Auth::user());
    return View('auth.users.organizer.usermanager')->with('profile', $profile);
  }


}
