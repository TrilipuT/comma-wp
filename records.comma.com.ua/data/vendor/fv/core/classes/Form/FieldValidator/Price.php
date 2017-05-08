<?php

class Form_FieldValidator_Price extends Form_FieldValidator {

    public function validate( Form_Field $field ) {
        $value = $field->getValue();

        if( empty($value) ){
            return true;
        }

        if( !is_numeric($value) ){
            return false;
        }

        $v = round( floatval($value), 2 );

        if( (string)$v !== $value ){
            return false;
        }

        $field->setValue( $v );

        return true;
    }

}