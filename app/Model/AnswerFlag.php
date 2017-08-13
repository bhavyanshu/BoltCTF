<?php

namespace BoltCTF\Model;

use Illuminate\Database\Eloquent\Model;

class AnswerFlag extends Model
{
  protected $table = 'answer_flags';
  protected $primaryKey = 'answer_id';
  protected $foreignKey = 'ans_que_id';

  public function question()
  {
    return $this->belongsTo('BoltCTF\Model\Question','ans_que_id');
  }
}
