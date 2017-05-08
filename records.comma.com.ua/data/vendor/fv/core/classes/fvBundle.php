<?php
/**
 * Created by cah4a.
 * Time: 15:32
 * Date: 27.08.13
 */

class fvBundle {

    /** @var \Composer\Autoload\ClassLoader */
    static $loader;

    function __construct( $directory, $prefix = "", $prepend = false ){
        $sources = realpath($directory . "/classes");

        if( empty($prefix) ){
            if( ! in_array($sources, self::$loader->getFallbackDirs()) )
                self::$loader->add($prefix, $sources, $prepend);
        } else {
            self::$loader->addPsr4( rtrim($prefix, "\\") . "\\", $sources, $prepend );

            fvTemplateFinder::addTemplatesFolder($prefix, $directory . "/views");
        }

        fvSite::config()->loadFromFolder($directory . "/configs");

        fvAnnotationRoutes::addControllerFolder($directory . "/classes/Controller", $prefix);
    }


}