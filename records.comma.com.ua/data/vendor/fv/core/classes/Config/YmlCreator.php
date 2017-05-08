<?php
/**
 * Created by cah4a.
 * Time: 17:28
 * Date: 03.08.2013
 */

class Config_YmlCreator {

    private $data = array();

    public function __construct( array $data ){
        $this->setData($data);
    }

    public static function make( array $data ){
        return new static( $data );
    }

    public function getYml(){
        return $this->toYml( $this->data );
    }

    private function toYml( array $config, $level = 0 ){
        $result = "";

        if( $level > 10 ){
            throw new Exception("Max nested level {$level} reached");
        }

        $prev = false;
        foreach( $config as $key => $value ){
            if( is_bool($value) ){
                $value = ( $value ? "true" : "false" );
            }

            if( is_string($value) ){
                $value = trim($value);

                if( empty($value) )
                    $value = "~";
            }

            if( is_scalar($value) ){
                $result .= str_repeat(" ", 2 * $level) . "{$key}: {$value} \n";
                $prev = false;
                continue;
            }

            if( is_array($value) ){
                if( $prev ){
                    $result .= "\n";
                }

                $result .= str_repeat(" ", 2 * $level) . "{$key}: \n";
                $result .= $this->toYml( $value, $level+1 );

                $prev = true;
                continue;
            }

            throw new Exception("Unsupported variable type");
        }

        return $result;
    }

    public function saveToFile( $file ){
        file_put_contents( $file, $this->getYml() );
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData( $data ){
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(){
        return $this->data;
    }

}