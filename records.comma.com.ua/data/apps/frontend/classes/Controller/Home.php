<?php

class Controller_Home extends fvController
{

    /**
     * @route /
     */
    function indexAction( $all = false )
    {
        $this->view()->albums = Album::select()->useQModifiers(! $all)->orderBy("root.id DESC")->getIterator();
    }

}