<?php

namespace BoltCTF\Model;

use Illuminate\Database\Eloquent\Model;

class ChallengeFile extends Model
{
  protected $table = 'challenge_files';
  protected $primaryKey = 'f_id';
  protected $foreignKey = 'cha_f_id';

  public function challenge()
  {
    return $this->belongsTo('BoltCTF\Model\Challenge','cha_f_id');
  }

  public function user()
  {
    return $this->belongsTo('BoltCTF\Model\User', 'user_f_id');
  }
}
