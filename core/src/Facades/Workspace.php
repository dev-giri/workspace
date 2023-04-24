<?php

namespace Workspace\Facades;

use Illuminate\Support\Facades\Facade;

class Workspace extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'workspace';
    }
}
