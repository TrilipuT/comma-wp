<?php
/**
 * @author Iceman
 * @since 29.11.12 11:34
 */

class fvViewlet extends Component_Extended {

    function __construct( $template, $params = [] )
    {
        $this->view()->assignParams( $params );
        $this->setTemplateName( $template );
    }

    function getComponentName(){
        return "viewlet";
    }

}