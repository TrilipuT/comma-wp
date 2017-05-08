<?php
/**
 * Created by cah4a.
 * Time: 13:02
 * Date: 16.02.15
 */

namespace AdminBundle\EntityList;

use AdminBundle\Module\Base;
use AdminBundle\Module\Card\EntityList;
use fvSite;

class Admin extends EntityList
{
    public function __construct( Base $base )
    {
        parent::__construct( $base );

        $subroles = fvSite::config()->get("subroles." . fvSite::session()->getAdmin()->role->get());
        if( $subroles != "*" ){
            $this->query()->andWhereIn( "role", $subroles );
        }
    }


}