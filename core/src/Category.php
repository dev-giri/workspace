<?php

namespace Workspace;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function posts(){
    	return $this->hasMany('Workspace\Post');
    }
}
