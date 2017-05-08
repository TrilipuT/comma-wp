<?php
/**
 * Расширяет компонент, чтобы можно было цеплять js, css, meta
 * @author Iceman
 * @since 14.11.12 17:32
 */
abstract class Component_Extended extends fvComponent{
    /** @var array */
    protected $scripts = Array();

    /** @var array */
    protected $sheets = Array();

    /** @var array */
    protected $meta = Array();

    /** @return array */
    function getCSS(){
        return $this->sheets;
    }

    /** @return array */
    function getJS(){
        return $this->scripts;
    }

    /**
     * @param Strings $name
     * @param mixed $default
     *
     * @return mixed
     */
    function getMeta( $name = null, $default = null ){
        if( $name === null ){
            return $this->meta;
        }

        if( isset( $this->meta[$name] ) ){
            return $this->meta[$name];
        }

        return $default;
    }

    /**
     * @param Strings $sheetsUrl
     *
     * @return $this
     */
    function addCSS( $sheetsUrl = "", $media = null ){
        if( is_array($sheetsUrl) ){
            foreach( $sheetsUrl as $sheetMedia => $sheet ){
                $this->addCSS($sheet, $sheetMedia ?: $media);
            }
        } elseif( !empty( $sheetsUrl ) ){
            $this->sheets[md5( $sheetsUrl . $media )] = [
                "href" => $this->modify( $sheetsUrl ),
                "media" => $media
            ];
        }

        return $this;
    }

    public function modify($file){
        if( preg_match( "/\\:\\/\\//", $file ) )
            return $file;

        if( defined("WEB_ROOT") ){
            $webRoot = WEB_ROOT;
        } else {
            $webRoot = $_SERVER["DOCUMENT_ROOT"];
        }

        $filePath = $webRoot . "/" . ltrim($file, "/");

        if( ! file_exists( $filePath ) ){
            return $file;
        }

        return $file . "?" . filemtime( $filePath );
    }

    /**
     * @param Strings|array $scriptUrl
     *
     * @return $this
     */
    function addJS( $scriptUrl = "" ){
        if( is_array($scriptUrl) ){
            foreach( $scriptUrl as $script ){
                $this->addJS($script);
            }
        } elseif( !empty( $scriptUrl ) ){
            $this->scripts[md5( $scriptUrl )] = $this->modify( $scriptUrl );
        }

        return $this;
    }

    /**
     * @param $name
     * @param $content
     * @return $this
     */
    function addMeta( $name, $content ){
        $this->meta[$name] = array( "name" => $name, "content" => $content );

        return $this;
    }

    /**
     * Добавляет в себя все css/js/meta от расширяющего компонента.
     * @param Component_Extended $extender
     * @return $this
     */
    function extend( Component_Extended $extender ){
        foreach( $extender->getJS() as $file ){
            $this->addJS( $file );
        }

        foreach( $extender->getCSS() as $css ){
            $this->addCss( $css );
        }

        foreach( $extender->getMeta() as $meta ){
            $this->addMeta( $meta["name"], $meta["content"] );
        }

        return $this;
    }

    final public function mergeKeywords( $keywords ){
        $keywords = $this->normalizeKeywords( $keywords );
        $oldKeywords = $this->normalizeKeywords( $this->getMeta('keywords', []) );

        $keywords = array_unique( array_merge( $keywords, $oldKeywords ) );
        $this->addMeta( "keywords", implode(", ", $keywords) );

        return $this;
    }

    public static function normalizeKeywords( $keywords ){
        if( is_string($keywords) ){
            $keywords = explode(",", $keywords);
        }

        return array_map( function( $value ){
            return trim($value);
        }, $keywords );
    }


}
