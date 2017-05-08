<?php


class MiddleWare_Router extends fvMiddleWare {

    public function handle( fvRequest $request, fvResponse $response, fvMiddleWaresChain $chain ){
        $router = fvSite::app()->getRouter();

        $controllerClass = $router->getCurrentRoute()->getController();
        if( ! class_exists($controllerClass) ){
            throw new Exception( "Controller '$controllerClass' not found!" );
        }

        /** @var fvController $controller */
        $controller = new $controllerClass;
        if( ! $controller->getLayout() ){
            $controller->setLayout( fvSite::app()->getDefaultLayout() );
        }

        $response->setResponseBody( $controller->handle( $router->getCurrentRoute()->getAction(), $router->getUriParams() ) );

        $chain->next();
    }

}