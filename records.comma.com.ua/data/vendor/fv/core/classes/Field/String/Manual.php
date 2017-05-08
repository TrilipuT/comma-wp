<?php

class Field_String_Manual extends Field_String_File{

    public function isValid(){
        if( mime_content_type( $this->getTemporalFile() ) != 'application/pdf' ){
            return false;
        }

        return parent::isValid();
    }

    public function getRealPath( $web ){
        return ( $web ) ? fvSite::config()->get( "path.upload.web_manual_files" ) :
            fvSite::config()->get( "path.upload.manual_files" );
    }
}