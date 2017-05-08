<?php

class fvApp {

    private $name;
    private $defaultLayout;
    private $appUriPrefix;
    private $innerUri;
    /** @var fvRouter */
    private $router;
    /** @var fvRequest */
    private $request;
    private $innerPrefix = "";

    final function __construct( $name ) {
        $config = fvSite::config()->get("apps.{$name}");

        if( empty($config) )
            throw new Exception("App {$name} is not defined");

        $this->name = $name;
        $this->appUriPrefix = "/" . trim($config['uri'], "/");

        $this->loadBundles();

        new fvBundle( "apps/{$name}", "", true );
        fvTemplateFinder::addTemplatesFolder( "", "apps/{$name}/views" );

        $this->init();
    }

    protected function init(){

    }

    protected function loadBundles(){

    }

    static function make( fvRequest $request ){
        foreach( fvSite::config()->apps as $key => $value ){
            $uri = "/" . trim($value['uri'], "/");
            $appUri = "/^" . preg_quote( $uri, "/" ) . "/";
            if( preg_match( $appUri, $request->getCurrentUrl() ) > 0 ){
                $className = ucfirst($key) . "App";
                include "apps/{$key}/classes/{$className}.php";
                $app = new $className( $key );
                $app->setRequest( $request );
                return $app;
            }
        }

        throw new Error_PageNotFound( "Application not found" );
    }

    public function setRequest( fvRequest $request ){
        $this->request = $request;
        $appUri = "/^" . preg_quote( $this->appUriPrefix, "/" ) . "/";
        $this->innerUri = "/" . ltrim(preg_replace( $appUri, "", $request->getUri() ), "/");

        return $this;
    }

    /**
     * @return Strings
     */
    public function getInnerUri() {
        return $this->innerUri;
    }

    /**
     * @return Strings
     */
    public function getDefaultLayout(){
        return $this->defaultLayout;
    }

    /**
     * @return Strings
     */
    public function setDefaultLayout($layout){
        $this->defaultLayout = $layout;
        return $this;
    }

    /**
     * @return Strings
     */
    public function getName(){
        return $this->name;
    }

    /**
     * @return Strings
     */
    public function getAppUriPrefix() {
        return $this->appUriPrefix . $this->innerPrefix;
    }

    /**
     * @return fvRouter
     */
    public function getRouter() {
        if( empty($this->router) ){
            $this->router = new fvRouter( $this );
        }

        return $this->router;
    }

    /**
     * @return fvRequest
     */
    public function getRequest(){
        return $this->request;
    }

    public function setInnerPrefix( $prefix ){
        $this->innerPrefix = $prefix;
        $prefix = "/^" . preg_quote( $this->appUriPrefix . $prefix, "/" ) . "/";
        $this->innerUri = "/" . ltrim(preg_replace( $prefix, "", $this->getRequest()->getUri() ), "/");
        return $this;
    }
}