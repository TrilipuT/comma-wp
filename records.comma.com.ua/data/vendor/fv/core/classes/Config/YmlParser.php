<?php
/**
 * Created by cah4a.
 * Time: 17:28 3.08.2013
 */

class Config_YmlParser {

    private $file = null;

    function __construct( $file ){
        $this->file = $file;
    }

    public static function make( $file ){
        return new static($file);
    }

    public function parse(){
        if( ! file_exists( $this->file ) ){
            throw new Exception("Can't load config file '" . $this->file . "'. Terminating");
        }

        $configData = file( $this->file );
        $config = array();
        $configArray = & $config;
        $currentConfig = & $configArray;
        $path = array();

        foreach( $configData as $configLine ){
            $configLine = preg_replace( "/#.*$/", "", $configLine );

            if( strlen( trim( $configLine ) ) == 0 ){
                continue;
            }

            $indent = strlen( $configLine ) - strlen( ltrim( $configLine ) );
            $configLine = trim( $configLine );

            foreach( $path as $key => $value ){
                if( $key > $indent ){
                    unset($path[$key]);
                }
            }

            if( empty($path[$indent]) ){
                $path[$indent] = & $currentConfig;
            }
            else {
                $currentConfig = & $path[$indent];
            }

            $key = substr( $configLine, 0, strpos( $configLine, ":" ) );
            $value = substr( $configLine, strpos( $configLine, ":" ) + 1 );

            if( strlen( $value = trim( $value ) ) > 0 ){
                $currentConfig[$key] = $this->parseValue( $value );
            }
            else {
                if( ! isset($currentConfig[$key]) ){
                    $currentConfig[$key] = array();
                }
                $currentConfig = & $currentConfig[$key];
            }
        }

        return $config;
    }

    private function parseValue( $value ){
        $value = trim( $value );

        if( $value == "~" ){
            return "";
        }

        if( $value == "true" ){
            return true;
        }

        if( $value == "false" ){
            return false;
        }

        if( $value ){
            if( ($value{0} == "[") && ($value{strlen( $value ) - 1} == "]") ){
                $value = explode( ",", substr( $value, 1, strlen( $value ) - 2 ) );
                foreach( $value as &$oneValue ){
                    $oneValue = $this->parseValue( trim( $oneValue ) );
                }

                return $value;
            }

            if( ($value{0} == "{") && ($value{strlen( $value ) - 1} == "}") ){
                $value = explode( ",", substr( $value, 1, strlen( $value ) - 2 ) );

                $result = array();

                foreach( $value as $oneValue ){
                    $a_key = substr( $oneValue, 0, strpos( $oneValue, ":" ) );
                    $a_value = substr( $oneValue, strpos( $oneValue, ":" ) + 1 );

                    $result[trim( $a_key )] = $this->parseValue( trim( $a_value ) );
                }

                return $result;
            }
        }

        return $value;
    }

}