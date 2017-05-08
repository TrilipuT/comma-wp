<?php

abstract class fvStorage {

    /**
     * @param array $params
     * @return fvStorage|bool must return false if storage can not be worked
     */
    abstract public function create( array $params );

    abstract public function get( $key );

    abstract public function set( $key, $value );

    abstract public function remove( $key );

}