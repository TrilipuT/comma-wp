<?php


class Controller_NotFound extends fvController {

    function indexAction( Exception $exception ){
        $this->getResponse()->setStatus(404);
        $this->view()->message = $exception->getMessage();
    }

}