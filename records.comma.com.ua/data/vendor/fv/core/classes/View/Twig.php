<?php


class View_Twig extends fvView
{

    /** @return Twig_Environment */
    public static function twig()
    {
        /** Twig_Environment */
        static $twig;

        if( is_null( $twig ) ){
            $environmentParams = array();

            if( defined( 'FV_PRODUCTION' ) && FV_PRODUCTION ){
                $environmentParams['debug'] = false;
                $environmentParams['cache'] = "cache/twig";
            }
            else {
                $environmentParams['debug'] = fvSite::config()->get( "view.twig.debug", true );

                if( fvSite::config()->get( "view.twig.cache", false ) ){
                    $environmentParams['cache'] = "cache/twig";
                }
            }

            $twig = new Twig_Environment(new Twig_Loader_Filesystem, $environmentParams);
            $twig->addExtension( new View_Twig_fvExtensions );

            if( $environmentParams['debug'] ){
                $twig->addExtension( new Twig_Extension_Debug );
            }
        }

        return $twig;
    }

    public function render()
    {
        $oldUmask = umask( 0006 );

        self::twig()->getLoader()->setPaths( [ $this->getPath() ] );
        $result = self::twig()->render( "{$this->getView()}.{$this->getExtension()}", $this->getParams() );

        if( ! empty($oldUmask) ){
            umask( $oldUmask );
        }

        return $result;
    }

    public function getExtension()
    {
        return "twig";
    }


}