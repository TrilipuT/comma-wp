<?php

class Form_Field_File extends Form_Field
{

    private $path;
    private $accept = "*/*";

    function getPath(){
        return $this->path;
    }

    function setPath( $path ){
        $this->path = $path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * @param mixed $accept
     * @return $this
     */
    public function setAccept( $accept )
    {
        $this->accept = $accept;
        return $this;
    }
}