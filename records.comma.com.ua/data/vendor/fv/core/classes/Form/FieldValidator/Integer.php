<?php

class Form_FieldValidator_Integer extends Form_FieldValidator {

    public function validate( Form_Field $field ) {
        $value = $field->getValue();

        if( is_string($value) )
            $value = trim($value);

        if( empty($value) )
            return true;

        return preg_match("/\\D/", $value) == 0;
    }

}