<?php

namespace AdminBundle\Module\Card;

use AdminBundle\Module\ItemView as ModuleItemView;
use Field_Datetime_Ctime;
use Field_Datetime_Mtime;
use Field_String_Image;
use fvSite;

class ItemView extends ModuleItemView
{

    public function assignVars()
    {
        $this->view()->assignParams( array(
            "id" => $this->getEntity()->getId(),
            "name" => $this->getName(),
            "ctime" => $this->getCtime(),
            "mtime" => $this->getMtime(),
            "description" => $this->getDescription(),
            "image" => $this->getImage(),
            "isActive" => $this->getIsActive(),
        ) );
    }

    final protected function getFirstField( array $keys )
    {
        foreach( $keys as $key ){
            if( array_key_exists( $key, $this->getEntity()->getFields() ) ){
                if( $value = $this->getEntity()->getField( $key )->get() ){
                    return $value;
                }
            }
        }
        return null;
    }

    protected function getName()
    {
        if( ! empty($this->options["name"]) ){
            return $this->getEntity()->getValue( $this->options["name"] );
        }

        return $this->getFirstField( array( "name", "title", "login" ) );
    }

    protected function getCtime()
    {
        $fields = $this->getEntity()->getFields( 'Field_Datetime_Ctime' );
        if( $fields ){
            /** @var Field_Datetime_Ctime $field */
            $field = reset( $fields );
            return $field->asTimestamp();
        }
        return null;
    }

    protected function getMtime()
    {
        $fields = $this->getEntity()->getFields( 'Field_Datetime_Mtime' );
        if( $fields ){
            /** @var Field_Datetime_Mtime $field */
            $field = reset( $fields );
            return $field->asTimestamp();
        }
        return null;
    }

    protected function getIsActive()
    {
        if( array_key_exists( "isActive", $this->getEntity()->getFields() ) ){
            return "" . $this->getEntity()->getField( "isActive" )->get();
        }
        return null;
    }

    protected function getDescription()
    {
        if( ! empty($this->options["description"]) ){
            return $this->getEntity()->getValue( $this->options["description"] );
        }

        return $this->getFirstField( array( "forward", "description", "text", "caption" ) );
    }

    protected function getImage()
    {
        $fields = $this->getEntity()->getFields( 'Field_String_Image' );
        if( $fields ){
            /** @var Field_String_Image $field */
            $field = reset( $fields );
            if( $field->get() && $field->isFileExist() ){
                try{
                    return $field->change()->grab( 100, 100 )->render();
                } catch( \Exception $e ){
                    return $e->getMessage();
                }
            }
            return fvSite::config()->get( "app.defaultImage", "" );
        }
        return null;
    }

}