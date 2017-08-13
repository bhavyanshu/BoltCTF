<?php

namespace BoltCTF\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
  use Notifiable, HasApiTokens;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $fillable = [
    'username', 'email', 'password',
  ];

  /**
  * The attributes that should be hidden for arrays.
  *
  * @var array
  */
  protected $hidden = [
    'password', 'remember_token', 'api_token', 'confirmation_code'
  ];

  public function profile()
  {
    return $this->hasOne('BoltCTF\Model\Profile', 'user_id');
  }

  public function event()
  {
    return $this->hasMany('BoltCTF\Model\Event', 'createdBy_id');
  }

  public function submittedflag()
  {
    return $this->hasMany('BoltCTF\Model\SubmittedFlag', 'sbmt_user_id');
  }

  public function userpoint()
  {
    return $this->hasMany('BoltCTF\Model\Profile', 'upt_user_id');
  }
}
