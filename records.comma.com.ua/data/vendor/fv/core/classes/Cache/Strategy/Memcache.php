<?php
/**
 * Created by cah4a.
 * Time: 13:01
 * Date: 16.06.14
 */

class Cache_Strategy_Memcache extends Cache_Strategy {

    static $server = 'localhost';

    private $value;


    /**
     * @return Memcache
     */
    static function handler(){
        static $memcache;

        if( is_null($memcache) ){
            $memcache = new Memcache();
            if( ! @$memcache->connect(self::$server) ){
                $memcache = false;
            }
        }

        return $memcache;
    }

    static function available(){
        if( ! class_exists("Memcache", false) ){
            return false;
        }

        return self::handler() instanceof Memcache;
    }

    function getCreationTime()
    {
        if( isset($_GET["clearcache"]) )
            return 0;

        $v = $this->get();

        if( empty($v) )
            return 0;

        return $v["created"];
    }

    function persist( $mixed ){
        self::handler()->set( $this->getSpace() . "." . $this->getKey(), [
            "created" => time(),
            "data" => $mixed
        ] );
    }

    function load()
    {
        $value = $this->get();
        return $value["data"];
    }

    private function get(){
        if( is_null( $this->value ) ){
            $this->value = self::handler()->get( $this->getSpace() . "." . $this->getKey() );
        }

        return $this->value;
    }


}