<?php

abstract class fvMiddleWare {

    abstract public function handle( fvRequest $request, fvResponse $response, fvMiddleWaresChain $chain );

}