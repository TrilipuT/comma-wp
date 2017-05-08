<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Setup as FormSetup;

class Setup extends \fvController
{

    /**
     * @route /setup
     * @option security off
     */
    function indexAction()
    {
        $this->view()->form = $form = new FormSetup();

        if( $this->getRequest()->isPost() ){
            if( $form->handle( $this->getRequest() ) ){
                $this->getResponse()->redirect( $this->getRequest()->getRequestParameter( 'redirect', null, $this->path('index:index') ) );
                $this->useLayout( false );
                return false;
            }
        }
    }

}