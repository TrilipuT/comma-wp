<?php
/**
 * Created by cah4a.
 * Time: 18:47
 * Date: 12.03.14
 */

class MiddleWare_LanguageSelector extends fvMiddleWare {

    private $useAcceptLanguage = true;

    function __construct( $params ){
        if( isset( $params["useAcceptLanguage"] ) ){
            $this->useAcceptLanguage = $params["useAcceptLanguage"];
        }
    }

    public function handle( fvRequest $request, fvResponse $response, fvMiddleWaresChain $chain ){
        $currentLanguage = $this->getLanguage( $request->getUri() );

        if( $currentLanguage instanceof Language ){
            Language::getManager()->setCurrentLanguage( $currentLanguage );
            fvSite::app()->setInnerPrefix( $currentLanguage->code->get() );
            $chain->next();
            return;
        }

        if( ! $currentLanguage instanceof Language && $this->useAcceptLanguage ){
            $currentLanguage = $this->chooseLanguage();
        }

        if( ! $currentLanguage instanceof Language ){
            $currentLanguage = Language::getManager()->getDefaultLanguage();
        }

        $uri = "/" . $currentLanguage->code->get() . $_SERVER['REQUEST_URI'];
        $response->redirect( rtrim( $uri, "/" ) );
    }

    private function getLanguage( $uri ){
        $languages = Language::getManager()->getAll();

        foreach( $languages as $language ){
            if( preg_match( "/^\\/{$language->code}(\\/|$)/", $uri ) ){
                return $language;
            }
        }

        return null;
    }

    private function chooseLanguage(){
        foreach( $this->getAcceptLanguages() as $lang => $q ){
            foreach( Language::getManager()->getAll() as $language ){
                if( preg_match( "/" . $language->code . "/", $lang ) ){
                    return $language;
                }
            }
        }

        return null;
    }

    private function getAcceptLanguages()
    {
        if( !($list = strtolower( $_SERVER['HTTP_ACCEPT_LANGUAGE'] )) ){
            return [];
        }

        if( ! preg_match_all( '/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', $list, $list ) ){
            return [];
        }

        $languages = array_combine( $list[1], $list[2] );

        foreach( $languages as $n => $v ){
            $languages[$n] = $v ? (float)$v : 1.;
        }

        arsort( $languages, SORT_NUMERIC );

        return $languages;
    }

} 