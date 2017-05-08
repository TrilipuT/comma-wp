<?php

class Field_Price extends Field_Int {

    const DISCRETIZATION_POWER = 2;

    static function discretization(){
        return pow( 10, self::DISCRETIZATION_POWER );
    }

    public function asFloat(){
        if( is_null($this->get()) ){
            return null;
        }

        return $this->get() / self::discretization();
    }

    function set( $value ){
        if( is_float($value) ){
            $value = (int)(self::discretization() * $value);
        }

        parent::set($value);
    }


}