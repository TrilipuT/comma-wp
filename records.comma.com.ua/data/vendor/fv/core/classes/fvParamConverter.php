<?php

abstract class fvParamConverter
{

    private $name;

    abstract function get( $value );

    abstract function generate( $value );

    public function setName( $name )
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

}