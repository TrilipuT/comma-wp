<?php

/**
 * Created by cah4a.
 * Time: 02:30
 * Date: 22.10.15
 */
class Component_Header extends fvComponent
{
    function __construct()
    {
        $this->view()->lang = reset(Language::getManager()->getOtherLanguages());
        $code = Language::getManager()->getCurrentLanguage()->code->get();
        $this->view()->uri = preg_replace( "/^\\/{$code}(\\/|$)/", "$1", fvRequest::getInstance()->getUri() );
    }

    public function setTracks( array $tracks ){
        $this->view()->track = reset($tracks);
        $this->view()->tracks = $t = json_encode(array_values(array_map(function( Track $track ){
            return array(
                "id" => $track->getId(),
                "name" => $track->name->get(),
                "src" => $track->mp3->getRealPath( true ) . $track->mp3->__toString(),
                "duration" => $track->duration->get()
            );
        }, $tracks)));
    }


}