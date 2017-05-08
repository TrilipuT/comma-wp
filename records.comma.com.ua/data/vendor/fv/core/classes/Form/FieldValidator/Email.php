<?php

class Form_FieldValidator_Email extends Form_FieldValidator {

    public function validate( Form_Field $field ) {
        $value = $field->getValue();

        if( is_string($value) )
            $value = trim($value);

        if( empty($value) )
            return true;

        $pattern = "/^.+@.+\\..+$/";

        return preg_match( $pattern, $value ) > 0;
    }

}