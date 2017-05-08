<?php

class Form_FieldValidator_Min extends Form_FieldValidator {

    private $minValue = 0;
    private $equal = 0;

    function __construct( $minValue, $equal = false ){
        $this->minValue = $minValue;
        $this->equal = $equal;
    }

    public function validate( Form_Field $field ) {
        $value = $field->getValue();

        if( empty($value) )
            return true;

        if( !is_numeric($value) ){
            return false;
        }

        if( $value < $this->minValue )
            return false;

        if( $value == $this->minValue )
            return $this->equal;

        return true;
    }

}