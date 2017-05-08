<?php

namespace AdminBundle\Module\Card;

use AdminBundle\Module\Base as ModuleBase;
use fvRoot;

/**
 * Created by cah4a.
 * Time: 17:27
 * Date: 09.01.14
 */
class Base extends ModuleBase
{

    protected $defaultListClass = "AdminBundle\\Module\\Card\\EntityList";

    public function getSubmodules( fvRoot $entity )
    {
        $submodules = array();
        foreach( $this->option( "submodules", array() ) as $submoduleName => $keyName ){
            $submodules[] = new SubBase($submoduleName, $keyName, $entity->getId());
        }

        return $submodules;
    }

}