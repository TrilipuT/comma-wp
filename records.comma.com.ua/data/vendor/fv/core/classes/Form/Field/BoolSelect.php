<?php
/**
 * Created by PhpStorm.
 * User: iceman
 * Date: 18.11.13
 * Time: 18:02
 */

class Form_Field_BoolSelect extends Form_Field_Select{
    function __construct ( array $validators = array() ) {
        $this->setValues( array(
                          null => "null",
                          true => "true",
                          false => "false",
                          ) );
        $this->addValidators( $validators );
        $this->setTemplateName("select");
    }

    public function setMultiple( $multiple ){
        if( (bool)$multiple )
            throw new Exception("boolean field can't be multiple");
    }

    public function isSelected( $key ){
        // PHP приводит null в ключе ассоциативного масива к пустой строке.
        // для этого вводим дополнительную проверку
        if( is_null($this->getValue()) )
            return is_null($key) || trim($key) == "";

        return $this->getValue() == $key;
    }

} 