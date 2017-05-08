<?php


class fvAnnotationRoutes {

    private $eacceleratorState;
    private static $folders = array();

    public static function addControllerFolder( $folder, $namespace ){
        $folder = (string)$folder;

        if( ! isset(self::$folders[$folder]) )
            self::$folders[$folder] = $namespace;
    }

    public static function getControllerFolders(){
        return self::$folders;
    }

    /**
     * @return fvRoute[]
     */
    public function generateRoutes(){
        $this->turnOffOpCodeCaches();

        $routes = array();
        $controllers = $this->findControllers();

        foreach( $controllers as $controller ){
            $reflection = new ReflectionClass( $controller );

            foreach( $reflection->getMethods() as $method ){
                if( substr( $method->getName(), -strlen("Action") ) == "Action" ){
                    $routes[] = $this->buildRoute( $controller, $method );
                }
            }
        }

        $this->turnOnOpCodeCaches();

        return $routes;
    }

    private function findControllers( $folder = null, $namespace = null ){
        $result = array();

        if( is_null($folder) ){
            foreach( self::$folders as $folder => $namespace ){
                $result = array_merge($result, $this->findControllers($folder, $namespace));
            }
            return $result;
        }

        $folder = is_null($folder) ? $this->folder : $folder;
        $folder = rtrim( $folder , "/" . DIRECTORY_SEPARATOR);

        if( !is_dir($folder) ){
            return [];
        }

        foreach( glob("{$folder}/*", GLOB_MARK) as $line ){
            if( substr($line, -1) == DIRECTORY_SEPARATOR ){
                $result = array_merge($result, $this->findControllers($line, $namespace));
            } elseif( substr($line, -4) == ".php" )
                if( preg_match( "/Controller\\/.*/", $line, $matches) ){
                    $controller = preg_replace("/.php$/", "", $matches[0]);
                    if( !empty($namespace) ){
                        $controller = preg_replace("/\\/|\\\\/", "\\", $controller);
                        $result[] = $namespace . "\\" . $controller;
                    } else {
                        $controller = preg_replace("/\\/|\\\\/", "_", $controller);
                        $result[] = $controller;
                    }
                }
        }

        return $result;
    }

    public function turnOffOpCodeCaches(){
        $this->eacceleratorState = ini_get( "eaccelerator.enable" );
        ini_set( "eaccelerator.enable", 0 );
    }

    public function turnOnOpCodeCaches(){
        ini_set( "eaccelerator.enable", $this->eacceleratorState );
    }

    /**
     * @param ReflectionMethod $method
     * @return fvRoute
     */
    private function buildRoute( $controller, ReflectionMethod $method )
    {
        $doc = $method->getDocComment();

        $action = preg_replace( "/Action$/", "", $method->getName() );

        return fvRoute::make()
            ->setController( $controller )
            ->setAction( $action )
            ->setOptions( $this->extractOptions( $doc ) )
            ->setUris( $this->extractUris( $doc, $controller, $action ) )
            ->setDefaultParams( $this->extractDefaultParams( $method ) )
            ->setParamConverters( $this->extractParamConverters( $doc ) );
    }

    private function extractOptions( $doc ){
        preg_match_all( "/@option\\s*([^\\s]+)\\s(.*)/", $doc, $routeOptions );
        if( ! empty($routeOptions[1]) ){
            return array_combine( $routeOptions[1], $routeOptions[2] );
        }

        return [];
    }

    private function extractDefaultParams( ReflectionMethod $method ){
        $params = [];
        foreach( $method->getParameters() as $param ){
            if( $param->isDefaultValueAvailable() ){
                $params[$param->getName()] = $param->getDefaultValue();
            }
        }
        return $params;
    }

    private function extractUris( $doc, $controller, $action ){
        preg_match_all( "/@route\\s(.*)/", $doc, $routeDefinitions );

        if( ! empty($routeDefinitions[1]) ){
            return $routeDefinitions[1];
        }

        $uri = preg_replace( "/^(Controller_|.*\\\\Controller\\\\)/", "", $controller );
        $uri = preg_replace_callback( "/(_|\\\\)(\\w)/", function( $a ){
            return "/" . strtolower($a[2]);
        }, $uri );

        $uri = Strings::fromCamelCase( $uri, "-" );

        return [ strtolower( $uri . "/" . $action ) ];
    }

    private function extractParamConverters( $doc ){
        $converters = [];
        preg_match_all( "/@converter\\s+(.*)/", $doc, $matches );

        if( !empty($matches[1]) ){
            foreach( $matches[1] as $value ){
                list( $name, $class ) = explode(" ", trim($value), 2);
                $converters[ltrim($name, "$")] = $class;
            }
        }

        return $converters;
    }

}