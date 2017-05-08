<?php

class Form_Field_Tags extends Form_Field_RootSelect {

    private $lazy = false;

    /** @var fvRootManager */
    private $manager;

    function __construct( fvRootManager $manager, $validators = [] )
    {
        $this->manager = $manager;
        $this->view()->entityName = $manager->getEntity()->getEntity();

        if( $manager->select()->getCount() < 20 ){
            $values = $manager->getAll();
        } else {
            $this->view()->lazy = $this->lazy = true;
            $values = [];
        }

        parent::__construct( $values, $validators );
    }

    public function getRenderValues(){
        if( $this->lazy ){
            return self::toString( $this->manager->getByIds((array)$this->getValue()) );
        }

        return $this->getValues();
    }

}