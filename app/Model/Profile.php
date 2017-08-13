<?php

namespace BoltCTF\Model;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
  protected $table = 'profiles';
  protected $primaryKey = 'prof_id';
  protected $foreignKey = 'user_id';

  public function user()
  {
    return $this->belongsTo('BoltCTF\Model\User','user_id');
  }
}
