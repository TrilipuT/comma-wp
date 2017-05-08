<?php

class Form_FieldValidator_Float extends Form_FieldValidator {

    public function validate( Form_Field $field ) {
        $value = $field->getValue();

        if( is_string($value) )
            $value = trim($value);

        return is_numeric( $value );
    }

}