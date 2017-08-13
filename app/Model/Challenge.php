<?php

namespace BoltCTF\Model;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
  protected $table = 'challenges';
  protected $primaryKey = 'challenge_id';
  protected $foreignKey = 'cha_cat_id';

  public function category()
  {
    return $this->belongsTo('BoltCTF\Model\Category','cha_cat_id');
  }

  public function question()
  {
    return $this->hasMany('BoltCTF\Model\Question', 'que_cha_id');
  }

  public function challengefile()
  {
    return $this->hasMany('BoltCTF\Model\ChallengeFile', 'cha_f_id');
  }
}
