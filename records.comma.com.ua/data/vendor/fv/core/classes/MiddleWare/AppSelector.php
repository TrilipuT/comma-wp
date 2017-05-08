<?php


class MiddleWare_AppSelector extends fvMiddleWare {

    public function handle( fvRequest $request, fvResponse $response, fvMiddleWaresChain $chain ){
        $app = fvApp::make( $request );
        fvSite::initApp($app);

        $wares = fvSite::config()->get("app.middleWares");
        if( $wares ){
            foreach( array_reverse( $wares ) as $middleWare => $value ){
                if( $middleWare[0] != "\\" ){
                    $middleWare = "MiddleWare_" . $middleWare;
                }

                $chain->prependMiddleWare( new $middleWare( $value ) );
            }
        }

        $chain->next();
    }


}