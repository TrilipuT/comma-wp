<?php

class Session_Storage extends fvSession {

    /** @var fvStorage */
    private $storage;
    /** @var array */
    private $data = array();

    function __construct( $params ){
        if( empty($params['storage']) )
            throw new Exception("Storages not specified. Specify storages in priority order");

        foreach( $params['storage'] as $class => $params ){
            $class = "Storage_" . $class;

            if( ! class_exists( $class ) )
                throw new Exception("Storage class {$class} not exist");

            $this->storage = call_user_func( array( $class, "create" ), $params );

            if( $this->storage instanceof fvStorage )
                return;
        }

        throw new Exception("No available storage found");
    }


    protected function getByKey( $key ){
        if( ! isset($this->data[$key]) )
            return null;

        return $this->data[$key];
    }

    protected function setByKey( $key, $value ){
        $this->data[$key] = $value;
    }

    protected function readOpen(){
        $result = $this->storage->get( $this->getSessionKey() );
        if( is_array( $result ) ){
            $this->data = json_decode( $result['data'] );
            if( !is_array($this->data) )
                $this->data = array();
        }
    }

    protected function writeClose(){
        $this->storage->set( $this->getSessionKey(), array( "lastUpdated" => time(), "data" => json_encode( $this->data ), ) );
    }

    protected function destroyClose(){
        $this->storage->remove( $this->getSessionKey() );
    }


}