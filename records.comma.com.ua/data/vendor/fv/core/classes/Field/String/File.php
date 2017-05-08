<?php

class Field_String_File extends Field_String
{
    const NO_SOURCE = -1;

    public $acceptedTypes = "*.*;";
    protected $oldFile = null;

    public function getToken()
    {
        return md5( $this->name );
    }

    function isFileExist()
    {
        return file_exists( $this->getFilePath() );
    }

    /**
     * @param bool $web
     *
     * @return Strings
     */
    public function getTemporalPath( $web = true )
    {
        return ($web) ? fvSite::config()->get( "path.upload.web_temporal", "/upload/temp/" ) : fvSite::config()->get( "path.upload.temporal", $_SERVER['DOCUMENT_ROOT'] . "/upload/temp/" );
    }

    public function getTemporalFile( $web = false )
    {
        $path = $this->getTemporalPath( $web ) . $this->get();
        return $path;
    }

    public function getRealPath( $web )
    {
        return ($web) ? fvSite::config()->get( "path.upload.web_files", "/upload/files/" ) : fvSite::config()->get( "path.upload.files", $_SERVER['DOCUMENT_ROOT'] . "/upload/files/" );
    }

    function getFilePath()
    {
        if( $this->get() ){
            return $this->getRealPath( false ) . $this->get();
        }

        return null;
    }

    public function getPath( $web = false )
    {
        $directory = $this->getRealPath( $web ) . $this->get();
        return $directory;
    }

    public function upload()
    {
        if( !$this->isChanged() ){
            return true;
        }
        else {
            if( !is_null( $this->oldFile ) ){
                $this->delete( $this->oldFile );
                $this->oldFile = null;
            }
        }

        if( !file_exists( $this->getTemporalFile() ) ){
            if( file_exists( $this->getPath() ) ){
                return true;
            }

            throw new Field_Exception( "Temporal file is not exists!", null, null );
        }

        $fileExtention = strtolower( substr( strrchr( $this->get(), "." ), 1 ) );

        //        if( count( $this->getAcceptableExtensions() ) && !in_array( $fileExtention, $this->getAcceptableExtensions() ) ){
        //            unlink( $this->getTemporalPath( false ) . $fileName . "." . $fileExtention  );
        //            throw new Field_Exception( "Extension is not acceptable" );
        //        }

        $fileBaseName = $fileName = substr( basename( $this->get() ), 0, - strlen( $fileExtention ) - 1 );

        for( $i = 1; true; $i ++ ){
            if( $this->checkSource( $this->get() ) ){
                $this->set( $fileBaseName . "_" . $i . "." . $fileExtention );
                // $dfg = $this->getTemporalPath( false ) . $fileName . "." . $fileExtention;
                //$dfg2 = $this->getTemporalFile();
                rename( $this->getTemporalPath( false ) . $fileName . "." . $fileExtention, $this->getTemporalPath( false ) . $this->get() );
                $fileExtention = strtolower( substr( strrchr( $this->get(), "." ), 1 ) );
                $fileName = substr( basename( $this->get() ), 0, - strlen( $fileExtention ) - 1 );
            }
            else {
                break;
            }
        }

        return rename( $this->getTemporalFile(), $this->getPath() );
    }

    /**
     * Проверяет существует ли файл
     * @return boolean
     */
    protected function checkSource( $fileBase = null )
    {
        if( is_null( $fileBase ) ){
            $fileBase = $this->get();
            if( !$fileBase ){
                return false;
            }
        }

        if( !file_exists( $this->getRealPath( false ) . $fileBase ) ){
            return false;
        }

        return true;
    }

    public function delete( $fileBase = null )
    {
        $fileBase = ($fileBase) ? $fileBase : $this->get();
        if( $this->checkSource( $fileBase ) ){
            return unlink( $this->getRealPath( false ) . $fileBase );
        }
        return false;
    }

    public function set( $value )
    {
        $this->oldFile = $this->get();
        parent::set( $value );
    }

    public function beforeSave()
    {
        $this->upload();
    }


    //    protected function getAcceptableExtensions(){
    //        return array();
    //    }
}