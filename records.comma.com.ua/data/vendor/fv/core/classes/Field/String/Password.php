<?php

class Field_String_Password extends Field_String {

    function get(){
        if( $this->isChanged() )
            return Strings::hashPassword( parent::get() );

        return parent::get();
    }

    function verify( $password ){
        return Strings::checkPassword( $password, $this->get() );
    }

    function afterSave(){
        parent::set( $this->get() );
    }
}