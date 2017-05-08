<?php

class Field_Foreign_Creator extends Field_Foreign {

    function asMysql() {
        if ( fvSite::session()->adminId )
            return fvSite::session()->adminId;
        else
            return null;
    }

    function isChanged() {
        return is_null( $this->get() );
    }

}