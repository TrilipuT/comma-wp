<?php

namespace AdminBundle\Component;

use fvSite;
use fvLink;
use fvRoute;

class Menu extends \fvMenu
{

    public function getLabel( \fvMenuItem $item ){
        if( $labelClass = $item->getOption("label") ){
            return new $labelClass;
        }

        return null;
    }

    public function addItem( $key, $name, $link, $icon = null )
    {
        if( ! $this->hasAcl( $link ) ){
            return false;
        }

        return parent::addItem( $key, $name, $link, $icon );
    }

    public function hasAcl( $link ){
        $admin = fvSite::session()->getAdmin();
        $acl = null;

        if( is_string( $link ) ){
            if( preg_match( "/moduleDefault:list\\/module=(\\w+)/", $link, $matches ) ){
                if( ! $admin->hasAcl( $matches[1] ) ){
                    return false;
                }
            }

            $link = fvLink::build( $link );
        }

        if( $link instanceof fvLink ){
            $acl = $link->getRoute()->getParam( "acl" );
        }

        if( $link instanceof fvRoute ){
            $acl = $link->getParam( "acl" );
        }

        if( $acl ){
            if( ! $admin->hasAcl( $acl ) ){
                return false;
            }
        }

        return true;
    }

}