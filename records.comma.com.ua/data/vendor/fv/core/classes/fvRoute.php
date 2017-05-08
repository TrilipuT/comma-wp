<?php

class fvRoute
{

    /** @var Strings */
    private $action;

    /** @var Strings[] */
    private $uris = [ ];

    /** @var Strings */
    private $controller;

    /** @var array */
    private $options;

    /** @var Strings[] */
    private $defaultParams;

    /** @var Strings[] */
    private $paramConverters = [ ];

    public static function make( $array = [] )
    {
        $instance = new self;

        if( isset($array['controller']) ){
            $instance->setController( $array['controller'] );
        }

        if( isset($array['action']) ){
            $instance->setAction( $array['action'] );
        }

        if( isset($array['options']) ){
            $instance->setOptions( $array['options'] );
        }

        if( isset($array['uris']) ){
            $instance->setUris( $array['uris'] );
        }

        if( isset($array['defaultParams']) ){
            $instance->setDefaultParams( $array['defaultParams'] );
        }

        if( isset($array['paramConverters']) ){
            $instance->setParamConverters( $array['paramConverters'] );
        }

        return $instance;
    }

    /**
     * @param Strings $action
     *
     * @return $this
     */
    public function setAction( $action )
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return Strings
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param Strings $controller
     *
     * @return $this
     */
    public function setController( $controller )
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return Strings
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param array $params
     * @return fvLink
     */
    public function getLink( $params = [] )
    {
        $link = new fvLink();
        $link->setRoute( $this );
        $link->setParams( $params );
        return $link;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setOptions( $params )
    {
        $this->options = $params;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Strings $uri
     *
     * @return $this
     */
    public function setUris( $uris )
    {
        $this->uris = array();

        foreach( $uris as $uri ){
            $this->uris[] = "/" . trim( trim($uri), "/" );
        }

        return $this;
    }

	/**
	 * @return Strings[]
     */
    public function getUris()
    {
        return $this->uris;
    }

    public function getDefinition()
    {
        return $this->getController() . "::" . $this->getAction() . " ({$this->getUris()})";
    }

    public function getParam( $paramName, $default = null )
    {
        if( !isset($this->options[$paramName]) ){
            return $default;
        }

        return $this->options[$paramName];
    }

    public function isUriMatch( $uri )
    {
        foreach( $this->getUris() as $uriTemplate ){
            $params = $this->extractParams( $uri, $uriTemplate );

            if( $params !== false ){
                return $params;
            }
        }

        return false;
    }

    private function extractParams( $uri, $uriTemplate )
    {
        $uriSections = explode( "/", $uri );
        $templateSections = explode( "/", $uriTemplate );

        if( count( $uriSections ) != count( $templateSections ) ){
            return false;
        }

        $resultedParams = array();

        foreach( $uriSections as $key => $section ){
            $routeRegexp = preg_replace_callback( "/{\\\$  ([\\w\\_]+) :?(.*?)? }/x", function ( $matches ){
                if( !empty($matches[2]) ){
                    return "(?<{$matches[1]}>{$matches[2]})";
                }
                return "(?<{$matches[1]}>[-\\d\\w]+)";
            }, $templateSections[$key] );

            if( preg_match( "/^" . $routeRegexp . "$/A", $section, $matches ) == 0 ){
                return false;
            }

            foreach( $matches as $mKey => $value ){
                if( is_string( $mKey ) ){
                    $resultedParams[$mKey] = $value;

                    if( $converter = $this->getParamConverter( $mKey ) ){
                        $nValue = $converter->get( $value );

                        if( is_null( $nValue ) ){
                            return false;
                        }

                        $resultedParams[$converter->getName()] = $nValue;
                    }
                }
            }
        }

        return $resultedParams;
    }

    /**
     * @param array $defaultParams
     * @return $this
     */
    public function setDefaultParams( $defaultParams )
    {
        $this->defaultParams = $defaultParams;
        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function addDefaultParam( $key, $value )
    {
        $this->defaultParams[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultParams()
    {
        return $this->defaultParams;
    }

    /**
     * @return array
     */
    public function getDefaultParamValue( $key )
    {
        if( !isset($this->defaultParams[$key]) ){
            return null;
        }

        return $this->defaultParams[$key];
    }

    /**
     * @return array
     */
    public function hasDefaultParamValue( $key )
    {
        return isset($this->defaultParams[$key]);
    }

    public function getRouteParams()
    {
        $params = array();
        foreach( $this->getUris() as $uri ){
            preg_match_all( "/{\\\$  ([\\w\\_]+) :?(.*?)? }/x", $uri, $paramsNames );
            $params[$uri] = $paramsNames[1];
        }
        return $params;
    }

    static function __set_state( $array )
    {
        return self::make( $array );
    }

    public function setParamConverters( $converters )
    {
        $this->paramConverters = $converters;
        return $this;
    }

    public function getParamConverters()
    {
        return $this->paramConverters;
    }

    /**
     * @param $key
     * @return null|fvParamConverter
     */
    public function getParamConverter( $key )
    {
        if( empty($this->paramConverters[$key]) ){
            return null;
        }

        if( preg_match("/^\\$([^\\s]+)/", $this->paramConverters[$key], $matches) ){
            $name = $matches[1];
            $class = trim(substr( $this->paramConverters[$key], strlen($matches[0]) ));
        } else {
            $class = $this->paramConverters[$key];
        }

        if( preg_match( "/\\((.*)\\)/", $class, $params ) ){
            $params = explode(",", $params[1]);

            $params = array_map( function( $s ){
                return trim($s);
            }, $params);
        }

        $class = trim( preg_replace( "/\\(.*\\)/", "", $class ) );
        if( empty($name) ){
            $name = lcfirst($class);
        }
        $converterClass = "ParamConverter_" . ucfirst( $class );

        /** @var fvParamConverter $converter */
        if( empty($params) ){
            $converter = new $converterClass;
        }
        elseif( count( $params ) == 1 ) {
            $converter = new $converterClass( reset( $params ) );
        }
        elseif( count( $params ) == 2 ) {
            $p1 = array_shift( $params );
            $p2 = array_shift( $params );
            $converter = new $converterClass( $p1, $p2 );
        }
        else {
            $converter = ( new ReflectionClass( $converterClass ) )->newInstanceArgs( $params );
        }

        $converter->setName( $name );

        return $converter;
    }


}