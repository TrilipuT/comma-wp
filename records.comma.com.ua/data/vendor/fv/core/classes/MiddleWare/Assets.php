<?php


class MiddleWare_Assets extends fvMiddleWare
{

    private $folders = [];

    function __construct($options)
    {
        $this->folders = $options;
        if( !isset( $this->folders["fv"] ) ){
            $this->folders["fv"] = "vendor/fv/core";
        }
    }

    public function handle( fvRequest $request, fvResponse $response, fvMiddleWaresChain $chain )
    {
        if( preg_match( "/^\\/assets\\/(.+)$/", $request->getUri(), $matches ) ){
            foreach( $this->folders as $prefix => $path ){
                $prefix = rtrim($prefix, "/") . "/";
                if( substr( $matches[1], 0, strlen($prefix) ) != $prefix ){
                    continue;
                }

                $local = substr( $matches[1], strlen($prefix) );
                $asset = realpath( $path . "/assets/" . $local  );
                if( ! $asset ){
                    continue;
                }

                $response->setHeader( "Content-type", $this->getMimeType( $asset ) );
                $response->setResponseBody( file_get_contents( $asset ) );
                return;
            }

            foreach( fvBundle::$loader->getPrefixesPsr4() as $prefix => $paths ){
                $prefix = str_replace( "\\", "/", trim( $prefix, "\\" ) );
                $prefix = Strings::fromCamelCase( $prefix, "-" );

                $prefix = rtrim($prefix, "/") . "/";
                if( substr( $matches[1], 0, strlen($prefix) ) != $prefix ){
                    continue;
                }

                $local = substr( $matches[1], strlen($prefix) );

                foreach( $paths as $path ){
                    $asset = realpath( $path . "/../assets/" . $local );

                    if( !$asset ){
                        continue;
                    }

                    $response->setHeader( "Content-type", $this->getMimeType( $asset ) );
                    $response->setResponseBody( file_get_contents( $asset ) );
                    return;
                }
            }
        }

        $chain->next();
    }

    private function getMimeType( $file )
    {
        $ext = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );

        $mimeTypes = [
            "pdf" => "application/pdf",

            "zip" => "application/zip",
            "docx" => "application/msword",
            "doc" => "application/msword",
            "xls" => "application/vnd.ms-excel",
            "ppt" => "application/vnd.ms-powerpoint",

            "gif" => "image/gif",
            "png" => "image/png",
            "svg" => "image/svg+xml",
            "jpeg" => "image/jpg",
            "jpg" => "image/jpg",

            "mp3" => "audio/mpeg",
            "ogg" => "audio/ogg",
            "wav" => "audio/x-wav",

            "ogv" => "video/ogg",
            "mpeg" => "video/mpeg",
            "mpg" => "video/mpeg",
            "mpe" => "video/mpeg",
            "mov" => "video/quicktime",
            "avi" => "video/x-msvideo",
            "3gp" => "video/3gpp",

            "woff" => "application/x-woff",
            "ttf" => "application/x-font-ttf",
            "eot" => "application/vnd.ms-fontobject",

            "css" => "text/css",

            "jsc" => "application/javascript",
            "js" => "application/javascript",

            "htm" => "text/html",
            "html" => "text/html"
        ];

        if( isset($mimeTypes[$ext]) )
            return $mimeTypes[$ext];

        return mime_content_type($file);
    }


}