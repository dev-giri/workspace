<?php

namespace Workspace;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    //
    protected $table = 'themes';
    protected $fillable = ['name', 'folder', 'version'];

    public function options(){
    	return $this->hasMany('\Workspace\ThemeOptions', 'theme_id');
    }
}