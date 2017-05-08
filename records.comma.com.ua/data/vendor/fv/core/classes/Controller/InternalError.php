<?php


class Controller_InternalError extends fvController {

    function indexAction( Exception $exception ){
        $this->getResponse()->setStatus(500);

        if( defined('FV_DEBUG_MODE') && FV_DEBUG_MODE ){
            $this->setContent( Strings::parseException($exception) );
            return;
        }

        try {
            $this->view()->message = $exception->getMessage();
            $this->setTemplateName('error');
            $this->prerender();
        } catch ( Twig_Error_Loader $e ){
            $this->setContent( $exception->getMessage() );
        }
    }

}