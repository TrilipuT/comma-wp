<?php

class Field_Foreign_User extends Field_Foreign {

    function asMysql() {
        if ( fvSite::session()->getUser() )
            return fvSite::session()->getUser()->getId();
        elseif( $this->isNullable() )
            return null;

        throw new Exception("Not logged in");
    }

    function isChanged() {
        return is_null( $this->get() );
    }

}