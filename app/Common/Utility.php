<?php
namespace BoltCTF\Common;

use DateTime;

use BoltCTF\Model\User;
use BoltCTF\Model\Profile;
use BoltCTF\Model\Event;

use Illuminate\Support\Facades\Input;

class Utility {

  //XSS prevent
  public static function killXSS()
  {
    $sanitized = static::cleanArray(Input::get());
    Input::merge($sanitized);
  }

  public static function cleanArray($array)
  {
    $result = array();
    foreach ($array as $key => $value) {
        $key = strip_tags($key);
        if (is_array($value)) {
            $result[$key] = static::cleanArray($value);
        } else {
            $result[$key] = trim(strip_tags($value));
        }
    }
    return $result;
  }

  //Helper function - Get profile by role - table profiles
  public static function getProfile($gotuser) {
      $assocprofile = User::with('profile')->find($gotuser->id)->profile;
      return $assocprofile;
  }

  public static function hasEventExpired($eventobj) {
    $ev_end_time = new DateTime($eventobj->end_time);
    $now_time = new DateTime('now');
    if($ev_end_time > $now_time) {
      return false;
    }
    else {
      return true;
    }
  }
}
