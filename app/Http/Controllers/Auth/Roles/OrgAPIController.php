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

use Response;
use Validator;

class OrgAPIController extends Controller
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

  public function searchUser(Request $request) {
    try {
      $input = array(
        'username' => $request->username,
        'email' => $request->email
      );
      $rules = array(
        'email' => 'required_without:username',
        'username' => 'required_without:email'
      );
      $validator = Validator::make($input, $rules);

      if ($validator->fails()) {
        return Response::error($validator->getMessageBag()->toArray());
      }
      else {
        if($request->username) {
          $users = User::where('username', 'LIKE', '%' . $request->username . '%')->get();
        }
        else if($request->email) {
          $users = User::where('email', 'LIKE', '%' . $request->email . '%')->get();
        }
        else {
          $users = User::where('username', 'LIKE', '%' . $request->username)->orWhere('email', 'LIKE', '%' . $request->email)->get();
        }
        return Response::success(compact('users'));
      }
    }
    catch (Exception $e){
      return Response::error($e->getMessage);
    }
  }

  public function deleteUser(Request $request) {
    try {
      $input = array(
        'u_id' => $request->u_id
      );
      $rules = array(
        'u_id' => 'required|int'
      );
      $validator = Validator::make($input, $rules);

      if ($validator->fails()) {
        return Response::error($validator->getMessageBag()->toArray());
      }
      else {
        $users = User::where('id', '=', $request->u_id)->delete();
        return Response::success('User Deleted!');
      }
    }
    catch (Exception $e) {
      return Response::error($e->getMessage);
    }
  }

  public function editUser(Request $request) {
    try {
      $input = array(
        'u_id' => $request->u_id,
        'u_email' => $request->u_email,
        'u_username' => $request->u_username,
        'u_blocked' => $request->u_blocked,
        'u_confirmed' => $request->u_confirmed
      );
      $rules = array(
        'u_id' => 'required|int',
        'u_email' => 'required|email|max:255',
        'u_username' => 'required|max:255',
        'u_blocked' => 'required|boolean',
        'u_confirmed' => 'required|boolean'
      );
      $validator = Validator::make($input, $rules);

      if ($validator->fails()) {
        return Response::error($validator->getMessageBag()->toArray());
      }
      else {
        $user = User::where('id', '=', $request->u_id)->where('username', '=', $request->u_username)->firstOrFail();
        $user->username = $request->u_username;
        $user->email = $request->u_email;
        $user->blocked = $request->u_blocked;
        $user->confirmed = $request->u_confirmed;
        $user->save();
        return Response::success('User updated!');
      }
    }
    catch (Exception $e) {
      return Response::error($e->getMessage);
    }
  }

  public function addCategory(Request $request) {

    $input = array(
      'category_name' => $request->category_name,
      'ev_ref_guid' => $request->ev_ref_guid
    );
    $rules = array(
      'category_name' => 'required',
      'ev_ref_guid' => 'required'
    );
    $validator = Validator::make($input, $rules);

    if ($validator->fails()) {
      return Response::error($validator->getMessageBag()->toArray());
    }
    else {
      $event = Event::where('createdBy_id', '=', Auth::user()->id)->where('ref_guid', '=' ,$request->ev_ref_guid)->firstOrFail();
      $c = new Category;
      $c->event()->associate($event);
      $c->ref_guid = str_random(20);
      $c->category_name = $request->category_name;
      $c->save();
      return Response::success("Category added");
    }
  }

  public function updateCategory(Request $request) {

    $input = array(
      'category_name' => $request->category_name,
      'cat_ref_guid' => $request->cat_ref_guid,
      'ev_ref_guid' => $request->ev_ref_guid
    );
    $rules = array(
      'category_name' => 'required',
      'cat_ref_guid' => 'required',
      'ev_ref_guid' => 'required'
    );
    $validator = Validator::make($input, $rules);

    if ($validator->fails()) {
      return Response::error($validator->getMessageBag()->toArray());
    }
    else {
      $event = Event::where('createdBy_id', '=', Auth::user()->id)->where('ref_guid', '=' ,$request->ev_ref_guid)->firstOrFail();
      $c = Category::where('cat_ev_id', '=', $event->event_id)->where('ref_guid', '=', $request->cat_ref_guid)->firstOrFail();;
      $c->category_name = $request->category_name;
      $c->save();
      return Response::success("Category updated");
    }
  }

  public function reorderCategory(Request $request) {

    $input = array(
      'ev_ref_guid' => $request->ev_ref_guid,
      'cat_ref_guids' => $request->cat_ref_guids
    );
    $rules = array(
      'ev_ref_guid' => 'required',
      'cat_ref_guids' => 'required'
    );
    $validator = Validator::make($input, $rules);

    if ($validator->fails()) {
      return Response::error($validator->getMessageBag()->toArray());
    }
    else {
      $event = Event::where('createdBy_id', '=', Auth::user()->id)->where('ref_guid', '=' ,$request->ev_ref_guid)->firstOrFail();
      $cat_id_arr = $request->cat_ref_guids;

      foreach($cat_id_arr as $index => $cia) {
        $category = Category::where('cat_ev_id', '=', $event->event_id)->where('ref_guid', '=', $cia)->firstOrFail();
        $category->category_weight = $index;
        $category->save();
      }
      return Response::success("Categories reordered");
    }
  }

  public function addChallenge(Request $request) {
    $input = array(
      'challenge_name' => $request->challenge_name,
      'cat_ref_guid' => $request->cat_ref_guid,
      'ev_ref_guid' => $request->ev_ref_guid
    );
    $rules = array(
      'challenge_name' => 'required',
      'cat_ref_guid' => 'required',
      'ev_ref_guid' => 'required'
    );
    $validator = Validator::make($input, $rules);

    if ($validator->fails()) {
      return Response::error($validator->getMessageBag()->toArray());
    }
    else {
      $event = Event::where('createdBy_id', '=', Auth::user()->id)->where('ref_guid', '=' ,$request->ev_ref_guid)->firstOrFail();
      $category = Category::where('cat_ev_id', '=', $event->event_id)->where('ref_guid', '=', $request->cat_ref_guid)->firstOrFail();
      $c = new Challenge;
      $c->category()->associate($category);
      $c->ref_guid = str_random(20);
      $c->challenge_name = $request->challenge_name;
      $c->save();
      return Response::success("Challenge added");
    }
  }

  public function updateChallenge(Request $request) {
    $input = array(
      'challenge_name' => $request->challenge_name,
      'cha_ref_guid' => $request->cha_ref_guid
    );
    $rules = array(
      'challenge_name' => 'required',
      'cha_ref_guid' => 'required'
    );
    $validator = Validator::make($input, $rules);

    if ($validator->fails()) {
      return Response::error($validator->getMessageBag()->toArray());
    }
    else {
      $challenge = Challenge::where('ref_guid', '=', $request->cha_ref_guid)->firstOrFail();
      $event = $challenge->category->event;

      if($event->createdBy_id !== Auth::user()->id) { //check if authorized to edit
        return Response::error('', '403');
      }

      $challenge->challenge_name = $request->challenge_name;
      $challenge->save();
      return Response::success("Challenge updated");
    }
  }

  public function reorderChallenge(Request $request) {
    $input = array(
      'cat_ref_guid' => $request->cat_ref_guid,
      'cha_ref_guids' => $request->cha_ref_guids
    );
    $rules = array(
      'cat_ref_guid' => 'required',
      'cha_ref_guids' => 'required'
    );
    $validator = Validator::make($input, $rules);

    if ($validator->fails()) {
      return Response::error($validator->getMessageBag()->toArray());
    }
    else {
      $category = Category::where('ref_guid', '=', $request->cat_ref_guid)->firstOrFail();
      $cha_id_arr = $request->cha_ref_guids;

      foreach($cha_id_arr as $index => $cia) {
        $challenge = Challenge::where('cha_cat_id', '=', $category->category_id)->where('ref_guid', '=', $cia)->firstOrFail();
        $challenge->challenge_weight = $index;
        $challenge->save();
      }
      return Response::success("Challenges reordered");
    }
  }

  public function getChallenge($chaguid) {
    try {
      $challenge = Challenge::where('ref_guid', $chaguid)->get([
        'ref_guid',
        'challenge_name'
      ]);

      if($challenge->isEmpty()) {
        return Response::isEmpty();
      }
      else {
        return Response::success(compact('challenge'));
      }
    }
    catch (Exception $e){
      return Response::error($e->getMessage);
    }
  }

  public function getQAs($chaguid) {
    try {
      $challenge = Challenge::where('ref_guid', $chaguid)->firstOrFail();
      $questions = Question::with('answerflag')->where('que_cha_id', '=', $challenge->challenge_id)->orderBy('question_weight', 'ASC')->get();

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

  public function saveQA(Request $request) {

    $input = array(
      'question' => $request->question,
      'question_points' => $request->question_points,
      'cha_ref_guid' => $request->cha_ref_guid,
      'answers' => $request->answers
    );
    $rules = array(
      'question' => 'required',
      'question_points' => 'required',
      'cha_ref_guid' => 'required',
      'answers' => 'required'
    );
    $validator = Validator::make($input, $rules);

    if ($validator->fails()) {
      return Response::error($validator->getMessageBag()->toArray());
    }
    else {
      $challenge = Challenge::where('ref_guid', '=', $request->cha_ref_guid)->firstOrFail();
      $event = $challenge->category->event;

      if($event->createdBy_id !== Auth::user()->id) { //check if authorized to edit
        return Response::error('', '403');
      }

      $question = new Question;
      $question->question_text = $request->question;
      $question->question_points = $request->question_points;
      $question->ref_guid = str_random(20);
      $question->challenge()->associate($challenge);
      $question->save();

      $latest_question = $question;
      $answers_arr = $request->answers;

      foreach ($answers_arr as $a) {
        if(!empty(trim($a['text']))) {
          $af = new AnswerFlag;
          $af->question()->associate($question);
          $af->answer_text = $a['text'];
          $af->save();
        }
      }
      return Response::success("QA saved");
    }
  }

  public function updateQA(Request $request) {

    $input = array(
      'question' => $request->question,
      'question_points' => $request->question_points,
      'cha_ref_guid' => $request->cha_ref_guid,
      'answers' => $request->answers,
      'que_ref_guid' => $request->que_ref_guid
    );
    $rules = array(
      'question' => 'required',
      'question_points' => 'required',
      'cha_ref_guid' => 'required',
      'answers' => 'required',
      'que_ref_guid' => 'required'
    );
    $validator = Validator::make($input, $rules);

    if ($validator->fails()) {
      return Response::error($validator->getMessageBag()->toArray());
    }
    else {
      $challenge = Challenge::where('ref_guid', '=', $request->cha_ref_guid)->firstOrFail();
      $event = $challenge->category->event;

      if($event->createdBy_id !== Auth::user()->id) { //check if authorized to edit
        return Response::error('', '403');
      }

      $question = Question::where('que_cha_id', $challenge->challenge_id)->where('ref_guid', $request->que_ref_guid)->firstOrFail();
      $question->question_text = $request->question;
      $question->question_points = $request->question_points;
      $question->save();

      $deletedans = AnswerFlag::where('ans_que_id', $question->question_id)->delete();

      $latest_question = $question;
      $answers_arr = $request->answers;

      foreach ($answers_arr as $a) {
        $af = new AnswerFlag;
        $af->question()->associate($question);
        $af->answer_text = $a['text'];
        $af->save();
      }
      return Response::success("QA updated");
    }
  }

  public function reorderQAs(Request $request){
    $input = array(
      'cha_ref_guid' => $request->cha_ref_guid,
      'que_ref_guids' => $request->que_ref_guids
    );
    $rules = array(
      'cha_ref_guid' => 'required',
      'que_ref_guids' => 'required'
    );
    $validator = Validator::make($input, $rules);

    if ($validator->fails()) {
      return Response::error($validator->getMessageBag()->toArray());
    }
    else {
      $challenge = Challenge::where('ref_guid', '=', $request->cha_ref_guid)->firstOrFail();
      $que_id_arr = $request->que_ref_guids;

      foreach($que_id_arr as $index => $qia) {
        $question = Question::where('que_cha_id', '=', $challenge->challenge_id)->where('ref_guid', '=', $qia)->firstOrFail();
        $question->question_weight = $index;
        $question->save();
      }
      return Response::success("Questions reordered");
    }
  }

  public function fileUpload(Request $request) {
    $input = array(
      'cha_ref_guid' => $request->cha_ref_guid,
      'file' => $request->file
    );
    $rules = array(
      'cha_ref_guid' => 'required',
      'file' => 'required|mimes:zip,tar.gz,png,jpeg,jpg,bmp,gif,txt,pcap|max:20000'
    );
    $validator = Validator::make($input, $rules);

    if($validator->fails()) {
      return Response::json(['Invalid File'], '400');
    }
    else {
      $challenge = Challenge::where('ref_guid', '=', $request->cha_ref_guid)->firstOrFail();
      $file = $request->file;
      $storagePath = 'app/public/challenge/'.Auth::user()->username.'/';
      $originalname = $file->getClientOriginalName();
      $file->move(storage_path($storagePath), $originalname);
      $token_code = str_random(12);
      $fu = new ChallengeFile;
      $fu->user()->associate(Auth::user());
      $fu->challenge()->associate($challenge);
      $fu->f_name = $originalname;
      $fu->f_token = $token_code;
      $fu->save();
      return Response::success("File Uploaded!");
    }
  }

  public function fileDelete(Request $request) {
    $input = array(
      'cha_ref_guid' => $request->cha_ref_guid,
      'f_token' => $request->f_token
    );
    $rules = array(
      'cha_ref_guid' => 'required',
      'f_token' => 'required'
    );
    $validator = Validator::make($input, $rules);

    if ($validator->fails()) {
      return Response::error($validator->getMessageBag()->toArray());
    }
    else {
      try {
        $cf = ChallengeFile::where('f_token', $request->f_token)->firstOrFail();
        $cf->delete();
        $storagePath = 'public/challenge/' . $cf->user->username . '/';
        Storage::delete($storagePath.$cf->f_name);
        return Response::success("Deleted file");
      }
      catch (Exception $e) {
        return Response::error('Error deleting file');
      }
    }
  }

}
