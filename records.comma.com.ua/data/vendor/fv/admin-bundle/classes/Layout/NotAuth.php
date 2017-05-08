<?php

namespace AdminBundle\Layout;

use fvLayout;

class NotAuth extends fvLayout
{

    function __construct()
    {
        $this->addCSS( [
            "/assets/admin-bundle/kube200/css/kube.min.css",
            "/assets/admin-bundle/font-awesome/css/font-awesome.min.css",
            "/assets/admin-bundle/backend.css",
        ] );

    }

}