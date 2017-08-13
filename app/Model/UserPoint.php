<?php

namespace BoltCTF\Model;

use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
  protected $table = 'userpoints';
  protected $primaryKey = 'upt_id';
  protected $foreignKey = 'upt_user_id';

  public function user()
  {
    return $this->belongsTo('BoltCTF\Model\User','upt_user_id')->select(array('id', 'username'));
  }

  public function event()
  {
    return $this->belongsTo('BoltCTF\Model\Event','upt_ev_id');
  }
}
