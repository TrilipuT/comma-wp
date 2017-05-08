<?php


class MiddleWare_ExceptionHandler extends fvMiddleWare {

    public function handle( fvRequest $request, fvResponse $response, fvMiddleWaresChain $chain ){
        try {
            $chain->next();
        } catch ( Exception $e ){
            $controller = new Controller_InternalError();
            try{
                $controller->setLayout( fvSite::app()->getDefaultLayout() );
            } catch ( Exception $exception ) {
                // ничего не делаем, ибо зачем, если и так тысячи проблем
            }
            $response->setResponseBody( $controller->handle("index", array( "exception" => $e )) );
        }
    }

}