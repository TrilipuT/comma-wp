<?php

class Controller_Menu extends fvController
{

    /**
     * @route /all
     */
    function indexAction()
    {
        $this->view()->albums = Album::findAll();
    }

}