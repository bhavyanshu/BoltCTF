<?php

namespace BoltCTF\Http\Controllers\Auth\Roles;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use BoltCTF\Http\Controllers\Controller;
use Utility;
use BoltCTF\Model\User;
use BoltCTF\Model\Profile;
use BoltCTF\Model\Event;
use BoltCTF\Model\Category;
use BoltCTF\Model\Challenge;
use BoltCTF\Model\ChallengeFile;
use BoltCTF\Model\Question;
use BoltCTF\Model\AnswerFlag;
use BoltCTF\Model\SubmittedFlag;
use BoltCTF\Model\UserPoint;
use BoltCTF\Model\IncorrectFlag;

use Response;
use Validator;

class PlayerAPIController extends Controller
{
  public function __construct() {
    $this->middleware('auth:api');
  }

  public function getQuestions($chaguid) {
    try {
      $challenge = Challenge::where('ref_guid', $chaguid)->firstOrFail();
      $questions = Question::with(['submittedflag' => function($q) {
        $q->where('sbmt_user_id', Auth::user()->id);
      }])
      ->where('que_cha_id', '=', $challenge->challenge_id)->orderBy('question_weight', 'ASC')->get();

      if($questions->isEmpty()) {
        return Response::isEmpty();
      }
      else {
        return Response::success(compact('questions'));
      }
    }
    catch (Exception $e){
      return Response::error($e->getMessage);
    }
  }

  public function getCategories($eguid) {
    try {
      $event = Event::where('ref_guid', $eguid)->firstOrFail();

      if(Utility::hasEventExpired($event) == true) {
        return Response::isEmpty();
      }

      $category = Category::with(['challenge.question.submittedflag' => function($q) {
        $q->where('sbmt_user_id', Auth::user()->id);
      }])
      ->where('cat_ev_id', $event->event_id)->orderBy('category_weight', 'ASC')->get([
        'category_id',
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

  public function submitAnswer(Request $request) {
    $give_points = false; //assume answer is wrong!

    $input = array(
      'ev_ref_guid' => $request->ev_ref_guid,
      'answer' => $request->answer,
      'que_ref_guid' => $request->que_ref_guid
    );
    $rules = array(
      'answer' => 'required',
      'que_ref_guid' => 'required'
    );
    $validator = Validator::make($input, $rules);

    if ($validator->fails()) {
      return Response::error($validator->getMessageBag()->toArray());
    }
    else {
      $event = Event::where('ref_guid', $request->ev_ref_guid)->firstOrFail();

      if(Utility::hasEventExpired($event) == true) {
          return Response::isEmpty();
      }

      $question = Question::with('answerflag')->where('ref_guid', $request->que_ref_guid)->firstOrFail();
      $correct_answers = $question->answerflag;

      //check if user already submitted the flag
      $checkSubmittedFlag = SubmittedFlag::where('sbmt_user_id', Auth::user()->id)
          ->where('sbmt_que_id', $question->question_id)
          ->first();

      if($checkSubmittedFlag) {
          return Response::error("Already submitted");
      }

      foreach ($correct_answers as $ca) {
        if(strcasecmp($ca->answer_text, $request->answer) == 0) {
          $give_points = true;
          break;
        }
      }

      if($give_points == true) {
        $submitflag = new SubmittedFlag;
        $submitflag->question()->associate($question);
        $submitflag->user()->associate(Auth::user());
        $submitflag->sbmt_text = $request->answer;
        $submitflag->sbmt_points = $question->question_points;

        $getCurrentPoints = UserPoint::where('upt_user_id', Auth::user()->id)->where('upt_ev_id', $event->event_id)->first();
        if($getCurrentPoints) {
          $getCurrentPoints->upt_points += $question->question_points;
          $getCurrentPoints->save();
        }
        else {
          $userpoints = new UserPoint;
          $userpoints->user()->associate(Auth::user());
          $userpoints->event()->associate($event);
          $userpoints->upt_points = $question->question_points;
          $userpoints->save();
        }

        $submitflag->save();

        return Response::success(1);
      }
      else {
        $incorrectflag = new IncorrectFlag;
        $incorrectflag->question()->associate($question);
        $incorrectflag->user()->associate(Auth::user());
        $incorrectflag->event()->associate($event);
        $incorrectflag->if_text = $request->answer;
        $incorrectflag->save();

        return Response::success(0);
      }

    }
  }
}
