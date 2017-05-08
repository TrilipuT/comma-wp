<?php

class MiddleWare_PermissionError extends fvMiddleWare {

    public function handle( fvRequest $request, fvResponse $response, fvMiddleWaresChain $chain ){
        try {
            if( $acl = fvSite::app()->getRouter()->getCurrentRoute()->getParam("acl") ){
                $admin = fvSite::session()->getAdmin();

                if( $admin instanceof Admin ){
                    if( ! $admin->hasAcl( $acl ) ){
                        throw new Error_PermissionDenied;
                    }
                }
            }

            $chain->next();
        } catch ( Error_PermissionDenied $e ){
            $controller = new Controller_PermissionDenied();
            $controller->setLayout( fvSite::app()->getDefaultLayout() );
            $response->setResponseBody( $controller->handle("index", array( "exception" => $e )) );
        }
    }

}