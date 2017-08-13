<?php

namespace BoltCTF\Model;

use Illuminate\Database\Eloquent\Model;

class SubmittedFlag extends Model
{
  protected $table = 'submitted_flags';
  protected $primaryKey = 'sbmt_id';
  protected $foreignKey = 'sbmt_que_id';

  public function question()
  {
    return $this->belongsTo('BoltCTF\Model\Question', 'sbmt_que_id');
  }

  public function user()
  {
    return $this->belongsTo('BoltCTF\Model\User', 'sbmt_user_id');
  }
}
