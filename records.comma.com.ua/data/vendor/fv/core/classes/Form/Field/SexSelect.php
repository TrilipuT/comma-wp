<?php

class Form_Field_SexSelect extends Form_Field_Select {

    function __construct ( array $validators = array() ) {
        $this->setValues( array(
            null => "",
            Field_Sex::MALE => "male",
            Field_Sex::FEMALE => "female",
        ) );
        $this->addValidators( $validators );
    }

    public function getValues()
    {
        $values = parent::getValues();
        if( ! $this->isNullable() ){
            unset($values[null]);
        }
        return $values;
    }


    public function setMultiple( $multiple ){
        if( (bool)$multiple )
            throw new Exception("Sex field can't be multiple");
    }

    public function isSelected( $key ){
        // PHP приводит null в ключе ассоциативного масива к пустой строке.
        // для этого вводим дополнительную проверку
        if( is_null($this->getValue()) )
            return is_null($key) || trim($key) == "";

        if( $key === "0" ){
            return $this->getValue() === "0";
        }

        return $this->getValue() == $key;
    }

}