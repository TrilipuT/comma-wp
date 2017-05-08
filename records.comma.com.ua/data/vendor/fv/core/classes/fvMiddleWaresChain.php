<?php


final class fvMiddleWaresChain {

    /** @var fvMiddleWare[] */
    public $middleWares = array();

    function __construct( fvRequest $request, fvResponse $response ){
        $this->request = $request;
        $this->response = $response;
    }

    public function next(){
        /** @var $middleWare fvMiddleWare */
        $middleWare = array_shift( $this->middleWares );
        if( $middleWare instanceof fvMiddleWare ){
            $middleWare->handle( $this->request, $this->response, $this );
        }
    }

    public function appendMiddleWare( fvMiddleWare $ware ){
        $this->middleWares[] = $ware;
        return $this;
    }

    public function prependMiddleWare( fvMiddleWare $ware ){
        array_unshift($this->middleWares, $ware);
        return $this;
    }

}