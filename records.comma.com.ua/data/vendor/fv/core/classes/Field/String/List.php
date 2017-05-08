<?php

abstract class Field_String_List extends Field_String
{

    abstract function getList();

    public function is( $key )
    {
        return $this->get() == $key;
    }

}