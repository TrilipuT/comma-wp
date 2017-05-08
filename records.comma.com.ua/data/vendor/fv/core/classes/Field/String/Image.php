<?php

class Field_String_Image extends Field_String_File
{

    public $acceptedTypes = "*.jpg;*.jpeg;*.gif;*.png;";

    public function getRealPath( $web )
    {
        return ($web)
            ? fvSite::config()->get( "path.upload.web_images", "/upload/images/" )
            : fvSite::config()->get( "path.upload.images", $_SERVER['DOCUMENT_ROOT'] . "/upload/images/" );
    }

    function isExternal()
    {
        return preg_match( "|//|", $this->get() ) > 0;
    }

    public function __toString()
    {
        return $this->getRealPath( true ) . $this->get();
    }

    function change()
    {
        if( !$this->isFileExist() ){
            return new fvImageQuery( $_SERVER['DOCUMENT_ROOT'] . "/images/noimage.png", $_SERVER['DOCUMENT_ROOT'] );
        }

        return new fvImageQuery( $this->getFilePath(), $_SERVER['DOCUMENT_ROOT'] );
    }

    /** удалить все файлы */
    public function delete( $fileBase = null )
    {
        $fileBase = ($fileBase) ? $fileBase : $this->get();
        if( empty($fileBase) ){
            return false;
        }
        if( parent::delete( $fileBase ) ){
            $filePhrase = pathinfo( $fileBase );
            $files = glob( $this->getRealPath( false ) . $filePhrase["filename"] . "*" );
            foreach( $files as $file ){
                @unlink( $file );
            }

            return true;
        }

        return false;
    }

    public function download()
    {
        if( !$this->isExternal() ){
            throw new Exception( "Can't download non-external images" );
        }
        $href = $this->get();
        if( !preg_match( "/^\\w+\\:/", $href ) ){
            $href = "http:" . $href;
        }
        $image = file_get_contents( $href );
        $seed = "";
        $href = preg_replace( "/(\\?|#).*$/", "", $href );
        $ext = pathinfo( $href );
        do {
            $newFile = $ext['filename'] . ($seed ? "_" : "") . $seed . "." . $ext['extension'];
            $seed += rand( 0, 9 );
        } while( file_exists( $this->getRealPath( false ) . $newFile ) );

        file_put_contents( $this->getRealPath( false ) . $newFile, $image );
        parent::set( $newFile );
    }

    public function set( $value )
    {
        parent::set( $value );
    }

    public function beforeSave()
    {
        if( $this->isExternal() ){
            $this->download();
        }
        else {
            parent::beforeSave();
        }
    }


}

class EImageException extends Exception
{
}