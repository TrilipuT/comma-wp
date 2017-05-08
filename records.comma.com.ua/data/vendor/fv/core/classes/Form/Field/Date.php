<?php

class Form_Field_Date extends Form_Field_String {

    function __construct ( array $validators = array() ) {
        $this->addValidators( $validators );
    }

    function setValue( $value ){
        if( is_string($value) ){
            if( empty($value) ){
                return parent::setValue( null );
            }

            return parent::setValue( strtotime($value) );
        }

        if( is_int($value) ){
            return parent::setValue( $value );
        }

        if( is_null($value) ){
            return parent::setValue(null);
        }

        throw new Exception("Can't set datetime value from type " . gettype($value));
    }

}