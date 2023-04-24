<?php

namespace Workspace\Actions;

use TCG\Voyager\Actions\AbstractAction;

class TenantLoginAction extends AbstractAction
{
    public function getTitle()
    {
        return __('voyager::generic.login');
    }

    public function getIcon()
    {
        return 'voyager-ship';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getDataType()
    {
        return 'hostnames';
    }

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

            return [
                'class' => 'btn btn-sm btn-success pull-left login',
                'target' => '_blank'
            ];
        }
    }

    public function getDefaultRoute()
    {
        if (!in_array($_SERVER['SERVER_PORT'], [80, 443])) {
            $port = ":$_SERVER[SERVER_PORT]";
        } else {
            $port = '';
        }
        $route = '//'. $this->data->fqdn .$port. '/admin';

        return $route;
    }
}