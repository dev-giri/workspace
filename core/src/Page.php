<?php

namespace Workspace;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Page extends \TCG\Voyager\Models\Page implements HasMedia
{
    use InteractsWithMedia;

    protected $perPage = 50;
    protected $guarded = [];

    public function link(){
        return url('p/' . $this->slug);
    }

    public function image(){
        return Voyager::image($this->image);
    }
}
