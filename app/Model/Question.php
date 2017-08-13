<?php

namespace BoltCTF\Model;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
  protected $table = 'challenge_questions';
  protected $primaryKey = 'question_id';
  protected $foreignKey = 'que_cha_id';

  public function challenge()
  {
    return $this->belongsTo('BoltCTF\Model\Challenge','que_cha_id');
  }

  public function answerflag()
  {
    return $this->hasMany('BoltCTF\Model\AnswerFlag', 'ans_que_id');
  }

  public function submittedflag()
  {
    return $this->hasOne('BoltCTF\Model\SubmittedFlag', 'sbmt_que_id');
  }
}
