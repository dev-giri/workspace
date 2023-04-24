<?php

namespace Workspace;

class Workspace
{
	public function routes()
    {
        require __DIR__.'/../routes/web.php';
    }

    public function api()
    {
    	require __DIR__.'/../routes/api.php';
    }

}