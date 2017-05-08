<?php
/**
 * Created by cah4a.
 * Time: 13:01
 * Date: 16.06.14
 */

class Cache_Strategy_File extends Cache_Strategy {

    function getCreationTime()
    {
        if( ! file_exists( $this->getCacheFile() ) ){
            return 0;
        }

        return filemtime( $this->getCacheFile() );
    }

    function getCacheFolder(){
        return "./cache";
    }

    function getCacheSpaceFolder(){
        return "{$this->getCacheFolder()}/{$this->getSpace()}";
    }

    function persist( $mixed )
    {
        if( ! file_exists($this->getCacheFolder()) ){
            mkdir($this->getCacheFolder());
            chmod($this->getCacheFolder(), 0777);
        }

        if( ! file_exists($this->getCacheSpaceFolder()) ){
            mkdir($this->getCacheSpaceFolder());
            chmod($this->getCacheSpaceFolder(), 0777);
        }

        file_put_contents( $this->getCacheFile(), "<?php return " . var_export($mixed, true) . ";" );
        chmod($this->getCacheFile(), 0777);
    }

    function getCacheFile(){
        return "{$this->getCacheSpaceFolder()}/{$this->getKey()}.php";
    }

    function load()
    {
        return include $this->getCacheFile();
    }


}