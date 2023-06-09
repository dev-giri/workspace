<?php

namespace Workspace;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;

class Post extends Model
{
    public function link(){
    	return url('/blog/' . $this->category->slug . '/' . $this->slug);
    }

    public function user(){
        return $this->belongsTo('\Workspace\User', 'author_id');
    }

    public function image(){
    	return Voyager::image($this->image);
    }

    public function category(){
    	return $this->belongsTo('Workspace\Category');
    }
}
