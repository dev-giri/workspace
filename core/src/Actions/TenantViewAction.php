<?php

namespace Workspace\Actions;

use TCG\Voyager\Actions\ViewAction;

class TenantViewAction extends ViewAction
{
    public function getAttributes()
    {
        $fqdn = $this->data->fqdn; 
        $systemSite = \Workspace\Tenant::getRootFqdn();

        if ( $systemSite === $fqdn ) {
            return [
                'class' => 'hide',
            ];
        }
        else {
            return array_merge( parent::getAttributes(), [ 'target' => '_blank'] );
        }


    }

    public function getDefaultRoute()
    {
        if (!in_array($_SERVER['SERVER_PORT'], [80, 443])) {
            $port = ":$_SERVER[SERVER_PORT]";
        } else {
            $port = '';
        }

        $route = '//'. $this->data->fqdn .$port;

        return $route;
    }
}