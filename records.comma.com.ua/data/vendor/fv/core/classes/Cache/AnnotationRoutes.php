<?php
/**
 * Created by cah4a.
 * Time: 12:52
 * Date: 16.06.14
 */

class Cache_AnnotationRoutes extends fvCache {

    private $appName;

    function __construct( $appName )
    {
        $this->appName = $appName;
    }

    function load()
    {
        $factory = new fvAnnotationRoutes;
        return $factory->generateRoutes();
    }

    function getSpace()
    {
        return "routes";
    }

    function getKey()
    {
        return md5(getcwd() . $this->appName);
    }

    function getMTime()
    {
        $max = 0;

        foreach( fvAnnotationRoutes::getControllerFolders() as $folder => $namespace ){
            if( is_dir($folder) ){
                $max = max( $max, fileatime($folder) );
            }
        }

        return $max;
    }

} 