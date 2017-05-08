<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cah4a
 * Date: 21.06.13
 * Time: 13:10
 * To change this template use File | Settings | File Templates.
 */

abstract class fvDictionary {

    final public function translate( $string, array $params = array() ){
        $string = $this->getTranslate($string);
        return sprintf( $string, $params );
    }

    abstract function hasTranslate( $string );

    abstract protected function getTranslate( $string );

    abstract public function getAllTranslations();

}