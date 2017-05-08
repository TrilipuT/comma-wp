<?php

namespace AdminBundle\Module;
use fvRoot;

/**
 * Created by cah4a.
 * Time: 15:59
 * Date: 10.01.14
 */
abstract class ItemView extends \fvComponent
{

    /** @var fvRoot */
    private $entity;

    protected $options;

    final public function setOptions( array $options = array() )
    {
        $this->options = $options;
    }

    final public function getComponentName()
    {
        return "module";
    }

    final public function setEntity( fvRoot $entity )
    {
        $this->entity = $entity;
        $this->view()->entity = $entity;
        $this->clearContent();
        return $this;
    }

    final public function getEntity()
    {
        return $this->entity;
    }

}