<?php

namespace AdminBundle\Module;

use Exception;
use Form_Root;
use fvComponent;
use fvRoot;
use fvSite;
use fvUrlGenerator;

/**
 * Created by cah4a.
 * Time: 12:27
 * Date: 29.10.13
 */
abstract class Base extends fvComponent
{

    protected $defaultListClass = "Module_List";

    private $options;
    private $moduleName;
    private $list;
    private $form;

    /**
     * @param $moduleName
     * @return static
     * @throws Exception
     */
    public static function make( $moduleName )
    {
        if( empty($moduleName) ){
            throw new \Exception("Module parameter not set");
        }

        if( ! fvSite::config()->get( "modules.{$moduleName}" ) ){
            throw new \Exception("Default module '{$moduleName}' not specified!");
        }

        $className = fvSite::config()->get( "modules.{$moduleName}.class", "AdminBundle\\Module\\Card\\Base" );

        if( ! is_subclass_of( $className, "AdminBundle\\Module\\Base" ) ){
            throw new \Exception("Module class '{$className}' must be subclass of AdminBundle\\Module\\Base!");
        }

        return new $className($moduleName);
    }

    public function getComponentName()
    {
        return "module";
    }

    function __construct( $moduleName )
    {
        $this->moduleName = $moduleName;
        $this->setOptions( fvSite::config()->get( "modules.{$moduleName}", array() ) );

        $listClass = $this->option( "listClass", $this->defaultListClass );
        $this->view()->list = $this->list = new $listClass($this);
    }

    /**
     * @return array
     */
    function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Strings $name
     * @param mixed $default
     * @return mixed
     */
    function option( $name, $default = null )
    {
        $settings = $this->getOptions();

        $names = explode( ".", $name );
        foreach( $names as $name ){
            if( ! isset($settings[$name]) ){
                return $default;
            }
            $settings = & $settings[$name];
        }

        if( $settings !== null ){
            return $settings;
        }

        return $default;
    }

    private function setOptions( array $params )
    {
        $this->options = $params;
    }

    public function getEntityName()
    {
        return $this->option( "entity" );
    }

    public function getSubmodules( fvRoot $entity )
    {
        return null;
    }

    public function getFieldTranslation( $key ){
        $label = $this->getForm()->getName() . ".fields." . $key;
        $defaultLabel = "defaults.fields." . $key;
        $dictionary = $this->getForm()->getDictionary();

        if( $dictionary->hasTranslate( $defaultLabel ) && ! $dictionary->hasTranslate( $label ) ){
            return $dictionary->translate( $defaultLabel );
        }

        return $dictionary->translate( $label );
    }

    public function getSortFields(){
        $fields = [];

        foreach( $this->getRootManager()->getEntity()->getFields() as $key => $field ){
            if( $field instanceof \Field_Constraint ||
                $field instanceof \Field_References ||
                $field instanceof \Field_Bool ||
                $field instanceof \Field_Text ||
                $field instanceof \Field_Foreign_Creator ||
                $field instanceof \Field_Foreign_Modifier ||
                $field instanceof \Field_String_Password ){
                continue;
            }

            $fields[] = $key;
        }

        return $fields;
    }

    /**
     * @return \fvRootManager
     * @throws ]Exception
     */
    function getRootManager()
    {
        if( ! class_exists( $this->getEntityName() ) ){
            throw new \Exception("Entity class '{$this->getEntityName()}' not found");
        }

        if( ! is_subclass_of( $this->getEntityName(), "fvRoot" ) ){
            throw new \Exception("Entity class '{$this->getEntityName()}' must be subclass of fvRoot");
        }

        return call_user_func( array( $this->getEntityName(), "getManager" ) );
    }

    /**
     * @return EntityList
     */
    public function getList()
    {
        return $this->list;
    }

    public function getName()
    {
        return $this->moduleName;
    }

    /**
     * @param $id
     * @return Form_Root
     * @throws \Error_PageNotFound
     */
    public function getForm( $id = null )
    {
        if( isset($this->form) ) {
            return $this->form;
        }

        if( ! is_null( $id ) ){
            $entity = $this->getRootManager()->getByPk( $id );

            if( ! $entity instanceof fvRoot ){
                throw new \Error_PageNotFound;
            }
        }
        else {
            $entity = clone $this->getRootManager()->getEntity();
        }

        $form = $this->createForm( $entity );
        $admin = fvSite::session()->getAdmin();
        foreach( $form->getFields() as $key => $field ){
            if( ! $admin->hasAcl( $this->moduleName . ".fields." . $key ) ){
                $form->removeField($key);
            }
        }

        if( $admin->hasAcl($this->moduleName . ".remove") ){
            $form->view()->remove = $this->option( "remove", true );
        }

        return $this->form = $form;
    }

    /**
     * @param fvRoot $entity
     * @return Form_Root
     * @throws Exception
     */
    protected function createForm( fvRoot $entity )
    {
        $defaultForm = "Form_Root";
        if( $entity->isLanguaged() ){
            $defaultForm = "AdminBundle\\Form\\LocaledRoot";
        }

        $formClass = $this->option( "form.class", $defaultForm );
        $formParams = $this->option( "form.options", array() );

        if( ! is_subclass_of( $formClass, "Form_Root" ) && $formClass != "Form_Root" ){
            throw new Exception("Form class '{$formClass}' must be subclass of fvForm");
        }

        /** @var Form_Root $form */
        $form = new $formClass($entity, $formParams);
        $admin = fvSite::session()->getAdmin();
        foreach( $form->getFields() as $key => $field ){
            if( ! $admin->hasAcl( $this->moduleName . ".fields." . $key ) ){
                $form->removeField($key);
            }
        }
        return $form;
    }

    public function getOneUrl()
    {
        return fvUrlGenerator::get( "moduleDefault:one-list", array(
            "module" => $this->getName(),
            "id" => '$id',
        ) );
    }

    public function getOffsetUrl()
    {
        $params = array(
            "module" => $this->getName(),
            "offset" => $this->getList()->getOffset() + $this->getList()->getPerPage(),
        );
        if( $this->getList()->getSearch() ){
            $params["search"] = $this->getList()->getSearch();
        }
        return fvUrlGenerator::get( "moduleDefault:list", $params );
    }

    public function getCreateUrl()
    {
        return fvUrlGenerator::get( "moduleDefault:create", array(
            "module" => $this->getName()
        ) );
    }

    public function getRemoveUrl()
    {
        return fvUrlGenerator::get( "moduleDefault:remove", array(
            "module" => $this->getName(),
            "id" => '$id',
        ) );
    }

    public function getEditUrl()
    {
        return fvUrlGenerator::get( "moduleDefault:edit", array(
            "module" => $this->getName(),
            "id" => '$id',
        ) );
    }
}