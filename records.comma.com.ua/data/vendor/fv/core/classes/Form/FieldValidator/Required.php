<?php

class Form_FieldValidator_Required extends Form_FieldValidator {

    public function validate( Form_Field $field ) {
        $value = $field->getValue();

        if( is_string($value) )
            $value = trim($value);

        return !empty( $value );
    }

}