<?php

class Session_BuiltIn extends fvSession {

    protected function getByKey( $key ){
        return $_SESSION[$key];
    }

    protected function setByKey( $key, $name ){
        $_SESSION[$key] = $name;
        return $this;
    }

    protected function readOpen(){
        session_start();
        return $this;
    }

    protected function writeClose(){
        session_write_close();
        return $this;
    }


    protected function destroyClose(){
        session_destroy();
        return $this;
    }
}