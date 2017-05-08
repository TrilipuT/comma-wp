<?php

class Field_Sex extends Field_Bool {

    const MALE = true;
    const FEMALE = false;

    public function isMale(){
        return $this->get() === self::MALE;
    }

    public function get(){
        return parent::get();
    }

    public function isFemale(){
        return $this->get() === self::FEMALE;
    }

}