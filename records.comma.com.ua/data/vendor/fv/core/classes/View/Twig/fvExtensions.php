<?php

class View_Twig_fvExtensions extends Twig_Extension {

    /**
     * Returns the name of the extension.
     *
     * @return Strings The extension name
     */
    public function getName(){
        return "extensions for fv integration";
    }

    public function getFunctions() {
        return array(
            new Twig_SimpleFunction('path', array( $this, "path" )),
            new Twig_SimpleFunction('session', array( $this, "session" )),
            new Twig_SimpleFunction('request', array( $this, "request" )),
            new Twig_SimpleFunction('viewlet', array( $this, "viewlet" )),
        );
    }

    public function getFilters(){
        return array(
            new Twig_SimpleFilter('translate', array( $this, "translate" )),
        );
    }

    public function session(){
        return fvSite::session();
    }

    public function request(){
        return fvSite::app()->getRequest();
    }

    public function path( $link, array $params = array(), $simpleArray = false ){
        return fvUrlGenerator::get( $link, $params, $simpleArray );
    }

    public function translate( $link ){
        if( empty($link) || !is_string($link) )
            return $link;

        return fvSite::dictionary()->translate($link);
    }

    public function viewlet( $template, $params = [] ){
        $className = "Viewlet_" . preg_replace_callback( "/(\\/|\\-)(.)/", function( $m ){
            return ( $m[1] == "/" ? "_" : "") . strtoupper($m[2]);
        }, ucfirst($template) );

        if( ! class_exists($className) ){
            $className = "fvViewlet";
        }

        return (new $className( $template, $params ))->render();
    }

}