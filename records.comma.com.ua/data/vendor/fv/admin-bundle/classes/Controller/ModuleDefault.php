<?php

namespace AdminBundle\Controller;

use Admin;
use AdminBundle\Module\Base;
use Error_PageNotFound;
use Error_PermissionDenied;
use Field_String_File;
use Field_String_Password;
use Form_Field_Hidden;
use fvMultiController;
use fvRoot;
use fvSite;

final class ModuleDefault extends fvMultiController
{

    protected function init()
    {
        /** @var Admin $admin */
        $admin = fvSite::session()->getAdmin();

        if( ! $admin->hasAcl( fvSite::app()->getRouter()->getUriParam( 'module' ) ) ){
            throw new Error_PermissionDenied;
        }
    }

    /**
     * @route /module/{$module}/
     */
    function listAction()
    {
        $this->view()->module = $module = $this->getModule();

        if( $this->getRequest()->offset ){
            $module->getList()->offset( $this->getRequest()->offset );
        }

        if( $this->getRequest()->search ){
            $module->getList()->search( $this->getRequest()->search );
        }

        if( is_array( $this->getRequest()->sort ) ){
            $module->getList()->sort( $this->getRequest()->sort );
        }

        if( $this->getRequest()->isXmlHttpRequest() ){
            return $module->getList();
        }
    }

    /**
     * @return Base
     */
    function getModule()
    {
        if( ! isset($this->module) ){
            $this->module = Base::make( fvSite::app()->getRouter()->getUriParam( 'module' ) );
        }

        return $this->module;
    }

    /**
     * @route /module/{$module}/one/{$id}
     * @option ajax only
     */
    function oneListAction( $id )
    {
        $entity = $this->getModule()->getRootManager()->getByPk( $id );

        if( ! $entity instanceof fvRoot ){
            throw new Error_PageNotFound;
        }

        return $this->getModule()->getList()->getListView()->setEntity( $entity );
    }

    /**
     * @route /module/{$module}/remove/{$id}
     * @option ajax only
     *
     * @param $module
     * @param $id
     * @return mixed
     * @throws Error_PageNotFound
     */
    function removeAction( $id )
    {
        $entity = $this->getModule()->getRootManager()->getByPk( $id );

        if( ! $entity instanceof fvRoot ){
            throw new Error_PageNotFound;
        }

        $this->getResponse()->setHeader( 'success', $entity->delete() );

        return "";
    }

    /**
     * @route /module/{$module}/edit/{$id}
     * @option ajax only
     *
     * @param $module
     * @param $id
     * @return mixed
     * @throws Error_PageNotFound
     */
    function editAction( $id )
    {
        $form = $this->getModule()->getForm( $id );

        $form->setSubmitUrl( $this->getRequest()->getUri() );

        if( $keyName = $this->getRequest()->keyName ){
            $form->removeField( $keyName );
            $form->addField( $keyName, new Form_Field_Hidden($this->getRequest()->value) );
        }

        if( $this->getRequest()->isPost() ){
            $oldData = array(
                $form->getContainerName() => $form->getEntity()->toHash()
            );
            $form->handle( $this->getRequest() );
            $this->getResponse()->setHeader( 'success', $form->isProcessed() );
            $this->getResponse()->setHeader( 'id', $form->getEntity()->getId() );

            if( $this->getRequest()->isXmlHttpRequest() && $form->isProcessed() ){
                return json_encode( $oldData );
            }
        }

        $this->view()->form = $form;
        $this->view()->submodules = $this->getModule()->getSubmodules( $form->getEntity() );
    }

    /**
     * @route /module/{$module}/create
     * @option ajax only
     *
     * @param $module
     * @return mixed
     * @throws Error_PageNotFound
     */
    function createAction()
    {
        $this->view()->form = $form = $this->getModule()->getForm();

        if( $keyName = $this->getRequest()->keyName ){
            $form->removeField( $keyName );
            $form->addField( $keyName, new Form_Field_Hidden($this->getRequest()->value) );
        }

        $form->setSubmitUrl( $this->getRequest()->getUri() );

        if( $this->getRequest()->isPost() ){
            $form->handle( $this->getRequest() );
            $this->getResponse()->setHeader( 'success', $form->isProcessed() );
            $this->getResponse()->setHeader( 'id', $form->getEntity()->getId() );
            $this->getResponse()->setHeader( 'isNew', true );
        }

        $this->setTemplateName( "edit" );
    }


    /**
     * @route /search/{$entity}
     */
    function searchAction( $entity, $q ){
        $manager = \fvManagersPool::get($entity);

        $searchFields = array();
        foreach( $manager->getEntity()->getFields("Field_String") as $key => $field ){
            if( $field instanceof Field_String_File || $field instanceof Field_String_Password ){
                continue;
            }

            $searchFields[] = "{$key} LIKE :search";
        }

        /** @var fvRoot[] $items */
        $items = $manager
            ->select()
            ->where( implode( " OR ", $searchFields ), array( "search" => "%{$q}%" ) )
            ->limit(10)
            ->fetchAll();

        $result = [];
        foreach( \Form_Field_RootSelect::toString( $items ) as $key => $value ){
            $result[] = [
                "value" => $key,
                "text" => $value
            ];
        }

        return json_encode($result);
    }

}