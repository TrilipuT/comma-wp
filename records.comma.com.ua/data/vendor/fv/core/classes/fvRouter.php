<?php


class fvRouter {

    /** @var fvApp  */
    private $app;
    private $route;
    private $routes;
    private $params = array();

    public function __construct( fvApp $app ){
        $this->app = $app;
    }

    /**
     * @return fvApp
     */
    public function getApp(){
        return $this->app;
    }

    /**
     * @return fvRoute
     * @throws Error_PageNotFound
     */
    public function getCurrentRoute(){
        if( empty( $this->route ) ){
            foreach( $this->getRoutes() as $route ){
                $this->params = array();

                if( $route->getParam('ajax') ){
                    if( $route->getParam('ajax') == "only" && ! $this->getApp()->getRequest()->isXmlHttpRequest() ){
                        continue;
                    }

                    if( $route->getParam('ajax') == "disabled" && $this->getApp()->getRequest()->isXmlHttpRequest() ){
                        continue;
                    }
                }

                if( $route->getParam('method') ){
                    if( strtolower($route->getParam('method')) != strtolower($this->getApp()->getRequest()->getRequestMethod()) )
                        continue;
                }

                if( ($params = $route->isUriMatch( $this->getApp()->getInnerUri() )) !== false ){
                    $this->params = $params;
                    $this->route = $route;
                    break;
                }
            }

            if( empty( $this->route ) ){
                throw new Error_PageNotFound( "Controller not found" );
            }
        }

        return $this->route;
    }

    /**
     * @return fvRoute[]
     */
    public function getRoutes(){
        if( empty($this->routes) ){
            $cache = new Cache_AnnotationRoutes( $this->getApp()->getName() );
            $this->routes = $cache->get();
        }

        return $this->routes;
    }

    public function getUriParams(){
        return $this->params;
    }

    public function getUriParam( $name, $default = null ){
        if( isset( $this->params[$name] ) )
            return $this->params[$name];

        return $default;
    }

    public function hasCurrentRoute(){
        return isset( $this->route );
    }

    public function getCurrentLink(){
        return $this->getCurrentRoute()->getLink( $this->getUriParams() );
    }
}