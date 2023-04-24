<?php

namespace Workspace\Actions;

use TCG\Voyager\Actions\DeleteAction;

class TenantDeleteAction extends DeleteAction
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
            return parent::getAttributes();
        }
    }
}