<?php

/**
 * Created by cah4a.
 * Time: 13:01
 * Date: 16.06.14
 */
abstract class Cache_Strategy
{

    private $space;
    private $key;

    final function __construct( $space, $key )
    {
        $this->space = $space;
        $this->key = $key;
    }

    static function make( $space, $key )
    {
        if( Cache_Strategy_Memcache::available() ){
            return new Cache_Strategy_Memcache($space, $key);
        }

        return new Cache_Strategy_File($space, $key);
    }

    abstract function getCreationTime();
    abstract function persist( $mixed );
    abstract function load();

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getSpace()
    {
        return $this->space;
    }



}