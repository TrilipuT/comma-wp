<?php

abstract class fvMultiController extends fvController {

    public function resolveTemplateName(){
        $template = str_replace( "_", "-", Strings::fromCamelCase($this->getActionName()) );
        $this->setTemplateName( $template );
    }

    public function getTemplateDir(){
        $dir = preg_replace("/^{$this->getComponentName()}_/i", "", get_class($this));
        $dir = preg_replace("/^.*\\\\{$this->getComponentName()}\\\\/i", "", $dir);

        $dir = preg_replace_callback("/_(\\w)/i", function( $a ){
            return "/" . strtolower($a[1]);
        }, $dir);

        $dir = preg_replace_callback("/([A-Z])/", function($a){
            return "-" . strtolower($a[1]);
        }, lcfirst($dir));

        return $dir;
    }

}