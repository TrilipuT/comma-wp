<?php
/**
 * Created by cah4a.
 * Time: 09:24
 * Date: 13.03.15
 */

namespace AdminBundle\Controller;

use Exception;
use Translit;

class Fv extends \fvController
{

    /**
     * @option security on
     */
    function cropAction( $src, $params = [] ){
        $file = $_SERVER["DOCUMENT_ROOT"] . "/upload/images/" . $src;
        $r = pathinfo( $file );
        ini_set("memory_limit", "1G" );

        $newFile = sprintf("t%s_%s.%s", time(), preg_replace("/^t\\d+_/", "", $r["filename"]), strtolower($r['extension']));

        $folder = "/upload/temp/";

        $image = new \Image($file);
        $image->crop( $params["width"], $params["height"], $params["x"], $params["y"] );

        $image->save( $_SERVER["DOCUMENT_ROOT"] . $folder . $newFile );

        return json_encode([
            "success" => true,
            "file" => $folder . $newFile
        ]);
    }

    /**
     * @option security on
     */
    function uploadAction()
    {
        $this->useLayout( false );
        try{
            return json_encode( [
                "success" => true,
                "file" => $this->upload()
            ] );
        } catch( Exception $e ){
            return json_encode( [
                "success" => false,
                "error" => $e->getMessage()
            ] );
        }
    }

    function upload( $uploadSubPath = '/upload/temp/' )
    {
        if( ! empty($_FILES) ){
            $tempFile = $_FILES['file']['tmp_name'];
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . $uploadSubPath;
            $r = pathinfo( $_FILES['file']['name'] );
            $trans = new Translit();

            $fileName = sprintf("t%s_%s.%s", time(), $trans->Transliterate( $r["filename"] ), strtolower($r['extension']));
            $targetFile = str_replace( '//', '/', $targetPath ) . $fileName;

            if( @move_uploaded_file( $tempFile, $targetFile ) ){
                return $uploadSubPath . $fileName;
            }
        }

        throw new Exception("File not uploaded");
    }

    /**
     * @option security on
     */
    function uploadbylinkAction()
    {
        $this->useLayout( false );
        $link = $this->getRequest()->link;

        $pathInfo = pathinfo( $link );

        $uploadSubPath = '/upload/temp/';
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . $uploadSubPath;

        $fileContent = file_get_contents( $link );

        $trans = new Translit;
        $fileName = $trans->Transliterate( $pathInfo["filename"] ) . date( "dmYHis" ) . "." . $pathInfo['extension'];
        $targetFile = $targetPath . $fileName;

        file_put_contents( $targetFile, $fileContent );

        return json_encode( array(
            "success" => true,
            "path" => $uploadSubPath . $fileName
        ) );
    }

    function persistAction()
    {
        $this->useLayout( false );
        return json_encode( array( "filelink" => $this->upload( "/upload/redactor/" ) ) );
    }
}