<?php

namespace BoltCTF\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $table = 'categories';
  protected $primaryKey = 'category_id';
  protected $foreignKey = 'cat_ev_id';

  public function event()
  {
    return $this->belongsTo('BoltCTF\Model\Event','cat_ev_id');
  }

  public function challenge()
  {
    return $this->hasMany('BoltCTF\Model\Challenge', 'cha_cat_id')->orderBy('challenge_weight', 'ASC');
  }
}
