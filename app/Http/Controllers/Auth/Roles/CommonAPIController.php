<?php

namespace BoltCTF\Http\Controllers\Auth\Roles;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use BoltCTF\Http\Controllers\Controller;

use BoltCTF\Model\User;
use BoltCTF\Model\Profile;
use BoltCTF\Model\Event;
use BoltCTF\Model\Category;
use BoltCTF\Model\Challenge;
use BoltCTF\Model\ChallengeFile;
use BoltCTF\Model\Question;
use BoltCTF\Model\AnswerFlag;
use BoltCTF\Model\UserPoint;

use Response;
use Validator;

class CommonAPIController extends Controller
{
  public function __construct() {
    $this->middleware('auth:api');
  }

  public function getUser() {
    try {
      $user = Auth::guard('api')->user();
      $profile = $user->profile;

      $user = array(
        'id' => $user->id,
        'username' => $user->username,
        'email' => $user->email,
        '_perm' => $user->role_id,
        'profile' => array(
          'first_name' => $user->first_name,
          'last_name' => $user->last_name,
          'bio' => $user->bio,
          'occupation' => $user->occupation
        )
      );

      return Response::success(compact('user'));
    }
    catch (Exception $e){
      return Response::error($e->getMessage);
    }
  }

  public function getEvents() {
    try {
      $event = Event::where('published', true)->get([
        'event_id',
        'ref_guid',
        'name as event_name',
        'description',
        'start_time',
        'end_time'
      ]);

      if($event->isEmpty()) {
        return Response::isEmpty();
      }
      else {
        return Response::success(compact('event'));
      }
    }
    catch (Exception $e){
      return Response::error($e->getMessage);
    }
  }

  public function getCategories($eguid) {
    try {
      $event = Event::where('published', true)->where('ref_guid', $eguid)->firstOrFail();
      $category = Category::where('cat_ev_id', $event->event_id)->orderBy('category_weight', 'ASC')->get([
        'ref_guid',
        'category_name'
      ]);

      if($category->isEmpty()) {
        return Response::isEmpty();
      }
      else {
        return Response::success(compact('category'));
      }
    }
    catch (Exception $e){
      return Response::error($e->getMessage);
    }
  }

  public function getChallenges($catguid) {
    try {
      $category = Category::where('ref_guid', $catguid)->firstOrFail();
      $challenges = Challenge::where('cha_cat_id', $category->category_id)->orderBy('challenge_weight', 'ASC')->get([
        'ref_guid',
        'challenge_name',
        'challenge_weight'
      ]);

      if($challenges->isEmpty()) {
        return Response::isEmpty();
      }
      else {
        return Response::success(compact('challenges'));
      }
    }
    catch (Exception $e){
      return Response::error($e->getMessage);
    }
  }

  public function getChallengeFiles($chaguid) {
    try {
      $challenge = Challenge::where('ref_guid', $chaguid)->firstOrFail();
      $challengefiles = ChallengeFile::where('cha_f_id', $challenge->challenge_id)->get(['f_name', 'f_token']);

      if($challengefiles->isEmpty()) {
        return Response::isEmpty();
      }
      else {
        return Response::success(compact('challengefiles'));
      }
    }
    catch (Exception $e){
      return Response::error($e->getMessage);
    }
  }

  public function getLeaderboardbyEvent($eguid, $limit = 15) {
    try {
      $event = Event::where('ref_guid', $eguid)->firstOrFail();
      $leaders = UserPoint::with('user')->where('upt_ev_id', $event->event_id)->orderBy('upt_points', 'DESC')->orderBy('updated_at', 'ASC')->paginate($limit);

      if($leaders->isEmpty()) {
        return Response::isEmpty();
      }
      else {
        return Response::success(compact('leaders'));
      }
    }
    catch (Exception $e) {
      return Response::error($e->getMessage);
    }
  }

}
