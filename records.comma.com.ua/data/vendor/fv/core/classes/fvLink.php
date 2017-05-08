<?php

class fvLink {

    /** @var fvRoute */
    private $route;
    /** @var array */
    private $params = array();

    public static function build( $link, array $params = null ){
        $instance = new fvLink;

        $linkParams = explode("/", $link, 2);
        if( count($linkParams) > 1 ){
            $link = array_shift($linkParams);
            $stringParams = array_shift($linkParams);
            $instance->parseParams( $stringParams );
        }

        if( ! is_null($params) ){
            $instance->setParams( $params );
        }

        $instance->setRoute( self::getRouteByLink( $link ) );

        return $instance;
    }

    public function parseParams( $string ){
        if( empty($string) )
            return $this;

        $params = explode("/", $string);
        foreach( $params as $param ){
            if( ($pos = strpos($param, "=")) !== false ){
                $paramName = substr($param, 0, $pos);
                $paramValue = substr($param, $pos+1);
                $this->setParam($paramName, $paramValue);
            } else
               throw new Exception("unknown link parameter {$param}");
        }
        return $this;
    }

    private static function getRoutes(){
        static $routes = [];

        if( empty($routes) ){
            foreach( fvSite::app()->getRouter()->getRoutes() as $route ){
                $routes[ self::generateLinkForRoute( $route ) ] = $route;
            }
        }

        return $routes;
    }

    /**
     * @param $link
     * @return fvRoute
     * @throws Exception
     */
    private static function getRouteByLink( $link ){
        if( strpos($link, ":") === false ){
            $link .= ":index";
        }

        $routes = self::getRoutes();

        if( isset( $routes[$link] ) ) {
            return $routes[$link];
        }

        throw new Exception("can't find route for {$link} link");
    }

    function __toString(){
        return $this->generateUrl();
    }

    /**
     * @return Strings
     * @throws Exception
     */
    function generateUrl(){
        foreach( array_reverse($this->getRoute()->getRouteParams()) as $uri => $params ){
            $values = $this->params;

            foreach( $params as $key ){
                if( $converter = $this->getRoute()->getParamConverter( $key ) ){
                    if( isset( $values[$converter->getName()] ) ){
                        $convertedValue = $converter->generate( $values[$converter->getName()] );

                        if( !empty($convertedValue) ){
                            $values[$key] = $convertedValue;
                            unset($values[$converter->getName()]);
                        }
                    }
                }

                if( ! isset($values[$key]) && empty($convertedParams[ $key ]) ){
                    continue 2;
                }

                if( $values[$key] == $this->getRoute()->getDefaultParamValue( $key ) ){
                    continue 2;
                }
            }

            return $this->generate( $uri, $params, $values );
        }

        throw new Exception("Can't generate link to {$this->getRoute()->getDefinition()} route");
    }

    private function generate( $uri, $params, $values ){
        foreach( $params as $name ){
            if ( isset( $values[$name] ) ) {
                $value = $values[$name];
            } else
                throw new Exception("Route parameter \${$name} not specified for '{$this->getRoute()->getDefinition()}' route");

            $uri = preg_replace("/{\\\$ {$name}  :?(.*?)? }/x", $value, $uri);
        }

        $otherParams = array_diff_key( $values, array_flip( $params ) );

        foreach( $otherParams as $key => $value ){
            if( ! $this->getRoute()->hasDefaultParamValue( $key ) )
                continue;

            if( $value == $this->getRoute()->getDefaultParamValue( $key ) ){
                unset( $otherParams[$key] );
            }
        }

        if( !empty($otherParams) ){
            return rtrim( fvSite::app()->getAppUriPrefix(), "/") . $uri . "?" . http_build_query($otherParams);
        }

        return rtrim( fvSite::app()->getAppUriPrefix(), "/") . $uri;
    }

    static function controllerTransformation( $controllerClass ){
        $controller = preg_replace("/^.*?Controller\\\\/", "", $controllerClass );
        $controller = preg_replace("/^Controller_/", "", $controller );
        return preg_replace_callback('/(_|\\\\)(\w)/', function( $match ){
            return "-". strtolower($match[2]);
        }, lcfirst($controller));
    }

    static function actionTransformation( $actionName ){
        $action = preg_replace('/Post$/', '', lcfirst($actionName));
        return preg_replace_callback('/[A-Z]/', function( $match ){
            return "-". strtolower($match[0]);
        }, lcfirst($action));
    }

    static function generateLinkForRoute( fvRoute $route, array $params = array() ){
        return
            self::controllerTransformation( $route->getController() )
            . ":" .
            self::actionTransformation( $route->getAction() );
    }

    function generateLink(){
        return self::generateLinkForRoute( $this->getRoute(), $this->getParams() );
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams( array $params ){
        foreach( $params as $name => $value ){
            $this->setParam($name, $value);
        }
        return $this;
    }

    /**
     * @param Strings $name
     * @param mixed $value
     *
     * @return $this
     */
    public function setParam( $name, $value ){
        $this->params[$name] = $value;
        return $this;
    }

    /**
     * @param Strings $name
     *
     * @return $this
     */
    public function getParam( $name ){
        if( ! isset($this->params[$name]) )
            return null;

        return $this->params[$name];
    }

    /**
     * @return array
     */
    public function getParams(){
        return $this->params;
    }

    /**
     * @param fvRoute $route
     * @return $this
     */
    public function setRoute( $route ){
        $this->route = $route;
        return $this;
    }

    /**
     * @return fvRoute
     */
    public function getRoute(){
        return $this->route;
    }


}