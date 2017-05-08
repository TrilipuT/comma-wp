<?php

class Field_Percent extends Field_Int {

    function asFloat(){
        return $this->get() / 100;
    }

}