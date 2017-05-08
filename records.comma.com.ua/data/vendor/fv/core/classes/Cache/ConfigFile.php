<?php
/**
 * Created by cah4a.
 * Time: 12:52
 * Date: 16.06.14
 */

class Cache_ConfigFile extends fvCache {

    private $file;

    function __construct( $file )
    {
        $this->file = $file;
    }

    function load()
    {
        return Config_YmlParser::make($this->file)->parse();
    }

    function getSpace()
    {
        return "configs";
    }

    function getKey()
    {
        return md5( $this->file );
    }

    function getMTime()
    {
        return filemtime( $this->file );
    }

} 