<?php

class fvFieldWidget extends Component_Extended{
    /** @var fvField */
    private $field;

    function __construct( fvField $field, $templateName = "base" ){
        $this->field = $field;
        $this->setTemplateDir( get_class( $this->field ) );
        $this->setTemplateName( $templateName );
    }

    function getComponentName(){
        return "field";
    }

    function getField(){
        return $this->field;
    }

    function setVariable( $name, $value ){
        $this->view()->$name = $value;
        return $this;
    }
}