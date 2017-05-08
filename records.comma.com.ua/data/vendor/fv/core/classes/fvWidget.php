<?php
/**
 * @author Iceman
 * @since 29.11.12 11:34
 */

class fvWidget extends Component_Extended{

    /**
     * @var fvRoot
     */
    private $entity;

    public function __construct( fvRoot $entity, $type = "base" ){
        $this->entity = $entity;
        $this->setTemplateName( $type );
        $templateDir = lcfirst($this->getEntity()->getEntity());
        $func = create_function('$matches', 'return "/" . strtolower($matches[1]);');
        preg_replace_callback( "/_(\\w)/", $func, $templateDir );
        $this->setTemplateDir( $templateDir );
    }

    public function assignParams( array $vars ){
        $this->view()->assignParams($vars);
        return $this;
    }

    /**
     * @return \fvRoot
     */
    public function getEntity(){
        return $this->entity;
    }

    function getComponentName(){
        return "widget";
    }
}