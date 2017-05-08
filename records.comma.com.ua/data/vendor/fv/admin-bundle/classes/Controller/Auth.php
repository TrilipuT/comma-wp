<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Auth\SignIn as SignInForm;
use fvController;
use fvSite;

class Auth extends fvController
{

    /**
     * @route /auth/sign-out
     */
    public function signOutAction()
    {
        fvSite::session()->destroy();

        $this->getResponse()->redirect( $this->getRequest()->getRequestParameter( 'redirect', null, $this->path( 'auth:sign-in' ) ) );

        return false;
    }

    /**
     * @route /auth/sign-in
     */
    public function signInAction()
    {
        $this->view()->form = $form = new SignInForm();

        if( $this->getRequest()->isPost() ){
            if( $form->handle( $this->getRequest() ) ){
                $this->getResponse()->redirect( $this->getRequest()->getRequestParameter( 'redirect', null, $this->path( 'index:index' ) ) );
                $this->useLayout( false );
                return false;
            }
        }
    }

}