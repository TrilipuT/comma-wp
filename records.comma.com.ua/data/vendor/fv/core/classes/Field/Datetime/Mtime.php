<?php

class Field_Datetime_Mtime extends Field_Datetime {

    function asMysql(){
        return date('Y-m-d H:i:s');
    }

    function isChanged(){
        return true;
    }
    
}