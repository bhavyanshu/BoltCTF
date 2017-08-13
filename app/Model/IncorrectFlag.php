<?php

namespace BoltCTF\Model;

use Illuminate\Database\Eloquent\Model;

class IncorrectFlag extends Model
{
  protected $table = 'incorrect_flags';
  protected $primaryKey = 'if_id';
  protected $foreignKey = 'if_user_id';

  public function user()
  {
    return $this->belongsTo('BoltCTF\Model\User','if_user_id')->select(array('id', 'username'));
  }

  public function question()
  {
    return $this->belongsTo('BoltCTF\Model\Question','if_que_id');
  }

  public function event()
  {
    return $this->belongsTo('BoltCTF\Model\Event','if_ev_id');
  }
}
