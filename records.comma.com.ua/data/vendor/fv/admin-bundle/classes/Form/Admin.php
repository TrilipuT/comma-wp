<?php
/**
 * Created by cah4a.
 * Time: 18:42
 * Date: 05.01.15
 */

namespace AdminBundle\Form;

use fvRoot;
use Form_Root;
use fvSite;

class Admin extends Form_Root
{
    function __construct( fvRoot $entity, array $params = array() )
    {
        $me = fvSite::session()->getAdmin();

        if( ! $me->hasAcl("admins.passwords") && $me->getId() != $entity->getId() ){
            $params["exclude"][] = "password";
        }

        $subroles = fvSite::config()->get("subroles." . $me->role->get());

        if( is_array($subroles) ){
            $subroles = array_intersect( $subroles, array_keys(fvSite::config()->get("roles")) );

        }

        if( empty($subroles) ){
            $params["exclude"][] = "role";
        }

        parent::__construct( $entity, $params );

        if( $this->hasField("role") && $subroles != "*" ){
            $this->getField("role")->setValues( array_combine( $subroles, $subroles ) );
        }

        if( $this->hasField("login") && ! $entity->isNew() ){
            $this->getField("login")->disabled();
        }
    }

}