<?php

namespace AdminBundle\Form\FieldValidator;

use Admin;
use Exception;
use Form_Field;
use Form_FieldValidator;

class AdminPasswordCheck extends Form_FieldValidator
{

    private $admin;

    public function validate( Form_Field $field )
    {
        try {
            $login = $field->getForm()->getField( 'login' );
        } catch (Exception $e) {
            return false;
        }

        if( ! $login->isValid() ){
            return true;
        }

        $admin = Admin::getManager()->select()->where( array(
            "isActive" => true,
            "login" => $login->getValue()
        ) )->fetchOne();

        if( ! $admin instanceof Admin ){
            return false;
        }

        if( $admin->password->verify( $field->getValue() ) ){
            $this->admin = $admin;
            return true;
        }

        return false;
    }

    /**
     * @return Admin
     */
    public function getAuthAdmin()
    {
        return $this->admin;
    }


}