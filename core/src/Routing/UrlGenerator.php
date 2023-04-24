<?php

namespace Workspace\Routing;

use Illuminate\Routing\UrlGenerator as LaravelUrlGenerator;

class UrlGenerator extends LaravelUrlGenerator
{
	public function asset($path, $secure = null)
    {
        if ($this->isValidUrl($path)) {
            return $path;
        }

        // Once we get the root URL, we will check to see if it contains an index.php
        // file in the paths. If it does, we will remove it since it is not needed
        // for asset paths, but only for routes to endpoints in the application.
        $root = $this->assetRoot ?: $this->formatRoot($this->formatScheme($secure));

        return $this->removeIndex($root).'/'.trim($path, '/');
    }
}
