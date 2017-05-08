<?php

class LanguageManager extends fvRootManager {

    /** @var Language[] */
    private $languages;

    /** @var Language */
    private $default;

    function __construct($entity){
        parent::__construct($entity);

        $this->languages = parent::getAll();

        foreach( $this->languages as $language ){
            if( $language->isDefault->get() ){
                $this->default = $language;
            }
        }

        if( empty($this->languages) ){
            throw new Exception("No languages found!");
        }
    }

    public function getOneByCode( $code ){
        foreach( $this->getAll() as $language ){
            if( $language->code->get() == $code ){
                return $language;
            }
        }

        throw new Exception("Language {$code} not found");
    }

    function getByPk( $id ){
        foreach( $this->getAll() as $language ){
            if( $language->getId() == $id ){
                return $language;
            }
        }

        throw new Exception("Language #{$id} not found");
    }

    function getAll(){
        return $this->languages;
    }

    function getOtherLanguages(){
        return array_diff_key( $this->getAll(), [ $this->getCurrentLanguage()->getId() => null ] );
    }

    function getDefaultLanguage(){
        return $this->default;
    }

    public function getCurrentLanguage(){
        if( isset($this->current) )
            return $this->current;

        return $this->getDefaultLanguage();
    }

    public function switchLanguage( $code ){
        $this->current = $this->getOneByCode( $code );
        return $this;
    }

    public function setCurrentLanguage( Language $language ){
        $this->current = $language;
        return $this;
    }
}