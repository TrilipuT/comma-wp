<?php

namespace AdminBundle\Form\Auth;

use AdminBundle\Form\Base;
use AdminBundle\Form\FieldValidator\AdminPasswordCheck;
use Form_Field_String;
use fvSite;

class SignIn extends Base
{

    function __construct()
    {
        $this->addFields( array(
            "login" => new Form_Field_String("text", array( 'required' )),
            "password" => new Form_Field_String("password", array( 'required', new AdminPasswordCheck )),
        ) );
    }

    protected function process()
    {
        /** @var AdminPasswordCheck $validator */
        $validator = $this->getField( "password" )->getValidator( '\\AdminBundle\\Form\\FieldValidator\\AdminPasswordCheck' );
        fvSite::session()->adminId = $validator->getAuthAdmin()->getPk();
        return true;
    }

}