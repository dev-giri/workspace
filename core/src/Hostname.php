<?php

namespace Workspace;

use Hyn\Tenancy\Models\Hostname as HynHostname;

class Hostname extends HynHostname
{
    protected $table = 'hostnames';

    public function getAppNameAttribute()
    {
        return str_replace(".".env('ROOT_DOMAIN_NAME','localhost'),"",$this->attributes['fqdn']); 
    }
}