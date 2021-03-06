<?php


class MiddleWare_WithWww extends fvMiddleWare {

    public function handle( fvRequest $request, fvResponse $response, fvMiddleWaresChain $chain ){
        if( preg_match("/^www\\./", $_SERVER['HTTP_HOST'] ) === 0 ){
            $response->setStatus( 301 );
            $response->redirect("www." . $_SERVER['HTTP_HOST'] . "/" . $_SERVER['REQUEST_URI']);
        } else
            $chain->next();
    }


}