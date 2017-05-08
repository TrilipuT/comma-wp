<?php

class Field_Int_Range extends Field_Int {

    function getList() {
        return range( 0 , 1024 );
    }

    function set( $value ){
        if( is_null($value) )
            return parent::set( null );
        else
            return parent::set( (int)$value );
    }

}