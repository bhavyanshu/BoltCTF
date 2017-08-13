<?php

namespace BoltCTF\Model;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
  protected $table = 'events';
  protected $primaryKey = 'event_id';
  protected $foreignKey = 'createdBy_id';

  public function user()
  {
    return $this->belongsTo('BoltCTF\Model\User','createdBy_id');
  }

  public function category()
  {
    return $this->hasMany('BoltCTF\Model\Category', 'cat_ev_id');
  }
}
