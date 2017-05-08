<?php

/**
 * Base class to store configs
 */
class fvConfig {

    protected $config = array();

    public function loadFromFolderUsingFileCache( $folder ){
        $folder = rtrim($folder, "/");
        $glob = glob($folder . "/*.yml");

        if( $glob === false )
            return;

        foreach( $glob as $file ){
            $this->load($file);
        }
    }

    public function loadFromFolder( $folder ){
        $folder = rtrim($folder, "/");

        if( ! is_dir($folder) ){
            return;
        }

        $cacheDir = new Cache_ConfigFolder( $folder );
        $this->config = array_replace_recursive( $this->config, $cacheDir->get() );
    }

    public function load( $fileName ){
        $cacheFile = new Cache_ConfigFile( $fileName );
        $this->config = array_replace_recursive( $this->config, $cacheFile->get() );
    }

    function __get( $name ){
        return $this->get( $name );
    }

    /**
     * @param $cPath
     * @param null $default
     * @return array|mixed|null
     */
    function get( $cPath, $default = null ){
        $path = explode( ".", $cPath );

        $result = $this->config;

        foreach( $path as $step ){
            if( isset($result[$step]) ){
                $result = $result[$step];
            }
            else {
                return $default;
            }
        }

        return $result;
    }

    function set( $cPath, $value ){
        $path = explode( ".", $cPath );
        $name = array_pop($path);

        $result = &$this->config;

        foreach( $path as $step ){
            if( ! isset($result[$step]) || is_scalar($result[$step]) ){
                $result[$step] = array();
            }

            $result = &$result[$step];
        }

        if( is_array($result[$name]) && is_array($value) )
            $result[$name] = array_replace_recursive($result[$name], $value);
        else
            $result[$name] = $value;

        return $this;
    }

}
