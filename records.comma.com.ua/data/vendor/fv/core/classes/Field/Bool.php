<?php

class Field_Bool extends fvField {

    function set( $value ){
        if( is_null($value) || (is_string($value) && strlen(trim($value)) == 0) )
            parent::set( $this->isNullable() ? null : false );
        elseif ($value)
            parent::set( true );
        else
            parent::set( false );
    }

    public function __toString(){
        if( $this->get() )
            return (string)fvDictionary::getInstance()->BOOLEAN_TRUE;
        else
            return (string)fvDictionary::getInstance()->BOOLEAN_FALSE;
    }

    function getSQlPart() {
        $default = "DEFAULT '".(int)$this->get()."'";
        return  "int(1) unsigned NOT NULL $default";
    }
}