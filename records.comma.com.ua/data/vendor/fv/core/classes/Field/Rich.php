<?php

class Field_Rich extends Field_Text {
    
    function transform(){
        return \Michelf\Markdown::defaultTransform( $this->get() );
    }
}