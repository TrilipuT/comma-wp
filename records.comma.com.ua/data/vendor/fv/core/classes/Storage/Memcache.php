<?php

class Storage_Memcache extends fvStorage {

    /** @var Memcache */
    private static $handler;

    function __construct(){
        if( empty(self::$handler) ){
            self::$handler = new Memcache;
            self::$handler->connect("localhost");
        }
    }


    /**
     * @param array $params
     * @return fvStorage|bool must return false if storage can not be worked
     */
    public function create( array $params ){
        $storage = new Storage_Memcache();
        return $storage;
    }

    public function get( $key ){
        return self::$handler->get( $key );
    }

    public function set( $key, $value ){
        self::$handler->set( $key, $value );
        return $this;
    }

    public function remove( $key ){
        self::$handler->delete( $key );
        return $this;
    }


}