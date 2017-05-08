<?php

class Form_FieldValidator_Equal extends Form_FieldValidator {

    private $fieldKey;

    function __construct( $fieldKey ){
        $this->fieldKey = (string)$fieldKey;
    }

    public function validate( Form_Field $field ) {
        $value = $field->getValue();
        $otherValue = $field->getForm()->getField($this->fieldKey)->getValue();

        return $value == $otherValue;
    }

}