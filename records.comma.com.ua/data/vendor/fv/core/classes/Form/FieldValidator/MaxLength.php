<?php

class Form_FieldValidator_MaxLength extends Form_FieldValidator {

    private $length;

    function __construct( $length ){
        $this->length = (int)$length;
        if( $this->length <= 0 )
            throw new Exception("Length cannot be less than zero");
    }

    public function validate( Form_Field $field ) {
        $value = $field->getValue();

        return mb_strlen( $value, "utf-8" ) <= $this->length;
    }

}