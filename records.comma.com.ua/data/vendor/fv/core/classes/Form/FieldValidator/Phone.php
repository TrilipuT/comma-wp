<?php
/**
 * Created by JetBrains PhpStorm.
 * User: iceman
 * Date: 09.09.13
 * Time: 16:56
 * To change this template use File | Settings | File Templates.
 */

class Form_FieldValidator_Phone extends Form_FieldValidator{

    public function validate( Form_Field $field ){
        $value = $field->getValue();

        if( is_string($value) )
            $value = trim($value);

        if( empty($value) )
            return true;

        $value = preg_replace("/[^\\d]/", "", $value);

        $pattern = "/^\+?3?8?0\d{9}$/i";
        return preg_match( $pattern, $value ) > 0;
    }
}