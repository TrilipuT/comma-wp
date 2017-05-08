<?php

class fvUrlGenerator {

    /**
     * @param Strings $link например products-all:view/id=1
     * @param array $params    параметры для генерации курлы-курлы
     *
     * @return Strings
     *
     * @throws Exception
     */
    static function get( $link, array $params = null, $absolute = false ){
        $link = fvLink::build($link);

        if( !is_null($params) )
            $link->setParams($params);

        $url = $link->generateUrl();

        if( $absolute ){
            $url = "http://". $_SERVER["HTTP_HOST"] . $url;
        }

        return $url;
    }
}