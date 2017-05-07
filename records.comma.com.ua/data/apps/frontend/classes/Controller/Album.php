<?php

class Controller_Album extends fvController
{

    /**
     * @route /{$url}
     *
     * @converter $url $album Entity(Album, url)
     */
    function indexAction(Album $album)
    {
        $this->view()->album = $album;

        $this->view()->counters = [
            "facebook" => CounterCache::getFb($album),
            "vkontakte" => CounterCache::getVk($album),
        ];

        $this->getLayout()->setTitle($album->artist . " " . $album->title);
        $this->getLayout()->getHeader()->setTracks($album->tracks->get());

        $this->getLayout()->view()->shareTitle = $album->shareTitle();
        $this->getLayout()->view()->shareDescription = $album->shareDescription();
        $this->getLayout()->view()->shareImage = $album->shareImage();
    }

}