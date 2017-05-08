<?php

class fvClassCatcher {

    private $cache = "";
    private $write = false;
    private $collect;
    private $folder;

    function __construct( $collect = false ){
        $this->collect = $collect;

        $this->folder = getcwd();

        if( file_exists( $this->getCachePath() ) && $this->collect ){
            $content = file_get_contents( $this->getCachePath() );
            $this->cache = preg_replace( "/^<\?(php)?/", "", $content );
        }
    }

    public function loadFromCache(){
        if( file_exists( $this->getCachePath() ) ){
            $file = $this->getCachePath();
            include $file;
        }
    }

    private function getCachePath(){
        return $this->folder . "/cache/classes";
    }

    public function addToCache( $file ){
        if( ! $this->collect )
            return;

        $content = trim(file_get_contents( $file ));
        $content = preg_replace( "/^<\?(php)?/", "", $content );

        if( preg_match( "/class .* extends /", $content ) > 0 ){
            $this->cache =  $this->cache . "\n### {$file} ###\n" . $content . "\n";
        } else
            $this->cache = "\n### {$file} ###\n" . $content . "\n" . $this->cache;

        $this->write = true;
    }

    function __destruct(){
        if( $this->write ){
            file_put_contents( $this->getCachePath(), "<?php" . $this->cache );
        }
    }

}