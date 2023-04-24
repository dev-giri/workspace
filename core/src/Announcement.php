<?php

namespace Workspace;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
	public function users(){
		return $this->belongsToMany('Workspace\User');
	}
}
