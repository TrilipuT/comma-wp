<?php

class Field_Datetime_Ctime extends Field_Datetime {
    
    function asMysql(){
        return date('Y-m-d H:i:s');
    }
    
    function isChanged(){
        return is_null( $this->get() );
    }
    
}