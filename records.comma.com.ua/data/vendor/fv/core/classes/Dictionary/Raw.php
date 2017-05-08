<?php

class Dictionary_Raw extends fvDictionary {

    public function getTranslate( $string ) {
        return $string;
    }

    public function getAllTranslations(){
        return array();
    }

}