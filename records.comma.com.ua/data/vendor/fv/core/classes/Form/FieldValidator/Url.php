<?php

class Form_FieldValidator_Url extends Form_FieldValidator {

    public function validate( Form_Field $field ) {
        $value = $field->getValue();

        if( is_string($value) )
            $value = trim($value);

        if( empty($value) ){
            return true;
        }

        $pattern = "/^[a-zA-Z0-9\\_\\-]{1,255}$/";

        return preg_match( $pattern, $value );
    }

}