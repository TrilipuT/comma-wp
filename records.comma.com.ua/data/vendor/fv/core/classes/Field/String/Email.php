<?php

class Field_String_Email extends Field_String {

    function set( $value ) {
        if( is_string($value) ) {
            $value = mb_strtolower($value, "utf-8");
        }

        parent::set( $value );
    }

    public function isValid() {
        $pattern = "/^[a-z0-9_\-\.]+@[a-z_\-\.]+\.[a-z]{2,3}$/i";
        return preg_match($pattern, $this->value);
    }

}