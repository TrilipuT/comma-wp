<?php

namespace AdminBundle\MiddleWare;

use Admin;
use fvMiddleWare;
use fvMiddleWaresChain;
use fvRequest;
use fvResponse;
use fvSite;
use fvUrlGenerator;

class Security extends fvMiddleWare
{

    private $redirect;
    private $authAction = "auth:sign-in";
    private $redirectParam = 'redirect';

    function __construct( $config )
    {
        if( isset($config['authAction']) ){
            $this->authAction = $config['authAction'];
        }

        if( isset($config['redirect']) ){
            $this->redirect = $config['redirect'];
        }
        else {
            $this->redirect = fvUrlGenerator::get( $this->authAction );
        }
    }

    public function handle( fvRequest $request, fvResponse $response, fvMiddleWaresChain $chain )
    {
        $secureArea = (fvSite::app()->getRouter()->getCurrentRoute()->getParam( 'security', "on" ) == "on");

        if( ! $secureArea || ($request->getUri() == $this->redirect) ){
            $chain->next();
            return;
        }

        if( fvSite::session()->getAdmin() instanceof Admin ){
            fvSite::app()->setDefaultLayout( "AdminBundle\\Layout\\Auth" );
            $chain->next();
            return;
        }

        $response->setStatus( 403 );
        $response->redirect( $this->redirect . "?" . $this->redirectParam . "=" . $request->getCurrentUrl() );
    }


}