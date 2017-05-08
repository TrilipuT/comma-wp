<?php
/**
 * Created by JetBrains PhpStorm.
 * User: iceman
 * Date: 31.07.13
 * Time: 11:08
 * To change this template use File | Settings | File Templates.
 */

namespace AdminBundle\ItemView;
use AdminBundle\Module\Card\ItemView;
use User_FB;
use User_Vk;

/**
 * Class ItemView_User
 * @method \User getEntity()
 */
class User extends ItemView
{

    function __construct()
    {
        $this->setTemplateName( "item-view" );
        $this->setTemplateDir( "card" );
    }

    protected function getName()
    {
        return $this->getEntity()->getFullName();
    }

    protected function getIcons(){
        if( $this->getEntity() instanceof User_FB ){
            return "<a href='//facebook.com/{$this->getEntity()->netId}' target='_blank'><i class='fa fa-facebook-square'></i></a>";
        }

        if( $this->getEntity() instanceof User_Vk ){
            return "<a href='//vk.com/id{$this->getEntity()->netId}' target='_blank'><i class='fa fa-vk'></i></a>";
        }

        return null;
    }

    protected function getDescription()
    {
        if( $this->getEntity() instanceof User_Vk || $this->getEntity() instanceof User_FB ){
            if( $this->getEntity()->netId->get() ){
                return $this->getEntity()->netId->get();
            }
        }

        return $this->getEntity()->email;
    }

}