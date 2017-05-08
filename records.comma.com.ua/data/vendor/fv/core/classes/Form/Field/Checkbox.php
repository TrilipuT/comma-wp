<?php

class Form_Field_Checkbox extends Form_Field {

    function __construct ( array $validators = array() ) {
        $this->addValidators( $validators );
    }

}