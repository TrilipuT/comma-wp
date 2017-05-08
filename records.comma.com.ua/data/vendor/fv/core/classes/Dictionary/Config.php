<?php

class Dictionary_Config extends fvDictionary
{

    private $config = array();
    private $saveTo = array();

    private $glue = ".";

    function __construct()
    {
        $this->config = fvSite::config()->get( "dictionary.translations" );
        $this->glue = fvSite::config()->get( "dictionary.params.glue", $this->glue );

        if( fvSite::config()->get( "dictionary.params.autosave" ) ){
            $this->saveTo = fvSite::config()->get( "dictionary.params.file" );
            if( !file_exists( $this->saveTo ) ){
                throw new Exception( "Autosave dictionary file is not exists!" );
            }
        }
    }

    function hasTranslate( $string )
    {
        list( $value, $key ) = $this->findTranslation( trim( $string, $this->glue ), $this->config );

        return ! empty( $value );
    }

    protected function getTranslate( $string )
    {
        list( $value, $key ) = $this->findTranslation( trim( $string, $this->glue ), $this->config );

        if( empty($value) ){
            return $key;
        }

        return $value;
    }

    private function findTranslation( $string, &$config )
    {
        if( ($pos = strpos( $string, $this->glue )) !== false ){
            $key = substr( $string, 0, $pos );
            $string = substr( $string, $pos + 1 );

            if( !is_array( $config[$key] ) ){
                $config[$key] = array();
            }

            return $this->findTranslation( $string, $config[$key] );
        }

        if( !isset($config[$string]) ){
            $config[$string] = "";
            Config_YmlCreator::make( array( "dictionary" => array( "translations" => $this->config ) ) )->saveToFile( $this->saveTo );
        }

        return [ $config[$string], $string ];
    }

    public function getAllTranslations()
    {
        return $this->config;
    }

}