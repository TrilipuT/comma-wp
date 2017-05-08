<?php

class Form_FieldValidator_Max extends Form_FieldValidator {

    private $maxValue = 0;
    private $equal = false;

    function __construct( $maxValue, $equal = false ){
        $this->maxValue = $maxValue;
        $this->equal = $equal;
    }

    public function validate( Form_Field $field ) {
        $value = $field->getValue();

        if( empty($value) )
            return true;

        if( !is_numeric($value) ){
            return false;
        }

        if( $value > $this->maxValue )
            return false;

        if( $value == $this->maxValue )
            return $this->equal;

        return true;
    }

}