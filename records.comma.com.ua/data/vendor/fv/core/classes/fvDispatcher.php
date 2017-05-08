<?php


class fvDispatcher {

    /** @var fvResponse */
    private $response;

    /** @var fvRequest  */
    private $request;

    function __construct() {
        $this->request = fvRequest::getInstance();
        $this->response = fvResponse::getInstance();
    }

    /**
     * @return fvResponse
     */
    public function dispatch(){
        $chain = new fvMiddleWaresChain( $this->request, $this->response );
        foreach( fvSite::config()->get("middleWares") as $middleWare => $value ){
            $middleWare = "MiddleWare_" . $middleWare;
            $chain->appendMiddleWare( new $middleWare( $value ) );
        }
        $chain->next();

        return $this->response;
    }

}