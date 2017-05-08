<?php
/**
 * Created by cah4a.
 * Time: 12:52
 * Date: 16.06.14
 */

class Cache_ConfigFolder extends fvCache {

    private $folder;

    function __construct( $folder )
    {
        $this->folder = $folder;
    }

    function load()
    {
        $glob = glob($this->folder . "/*.yml");

        if( $glob === false )
            return false;

        $config = [];
        foreach( $glob as $fileName ){
            $fileConfig = Config_YmlParser::make($fileName)->parse();
            $config = array_replace_recursive( $config, $fileConfig );
        }
        return $config;
    }

    function getSpace()
    {
        return "configs";
    }

    function getKey()
    {
        return md5( realpath($this->folder) );
    }

    function getMTime()
    {
        return filemtime( $this->folder );
    }

}