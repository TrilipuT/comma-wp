<?php

class fvTemplateFinder {

    static $folders = [];

    static function addTemplatesFolder( $namespace, $dir ){
        $namespace = ltrim( trim( $namespace, "\\" ) . "\\", "\\");

        self::$folders[] = [
            "namespace" => $namespace,
            "dir" => $dir
        ];
    }

    function __construct( fvComponent $class, $extension )
    {
        $className = get_class($class);

        $componentName = $class->getComponentName();
        $directory = $class->getTemplateDir();
        $name = $class->getTemplateName();

        $searched = [];

        do {

            foreach( self::$folders as $folder ){
                $namespace = $folder["namespace"];
                $dir = $folder["dir"];

                if( substr( $className, 0, strlen($namespace) ) != $namespace ){
                    continue;
                }

                $tail = substr( $className, strlen($namespace) );
                $tail = $this->removeComponentNamePrefix( $tail, $componentName );

                $root = $dir . "/" . $componentName;

                if( ! is_null($directory) ){
                    $template = trim($directory, "/");
                } else {
                    $template = $this->resolveDirectory( $tail );
                }

                if( ! is_null( $name ) ){
                    $template .= "/" . $name;
                } else {
                    $template .= "/" . $this->resolveName( $tail );
                }

                $template = ltrim($template, "/");

                $searched[] = $path = "{$root}/{$template}.{$extension}";

                if( file_exists($path) ){
                    $this->root = $root;
                    $this->template = $template;
                    return;
                }
            }

            $className = get_parent_class($className);

            if( ! $className || substr($className, 0, 2) == "fv" )
                break;

        } while( true );

        throw new Exception("can't find template for " . get_class($class) . " class. Searched: " . implode("; ", $searched));
    }

    public function getPath(){
        return $this->root;
    }

    public function getFileName(){
        return $this->template;
    }

    public function removeComponentNamePrefix( $className, $componentName ){
        $componentName = str_replace("/", "_", $componentName);
        return preg_replace("/^{$componentName}(_|\\\\)?/i", "", $className);
    }

    public function resolveDirectory( $className ){
        $className = preg_replace("/(_|\\\\|^)[^_\\\\]+$/", "", $className);

        $dir = preg_replace_callback("/(_|\\\\)(\\w)/i", function( $a ){
            return "/" . strtolower($a[2]);
        }, $className );

        return rtrim(Strings::fromCamelCase( $dir, "-" ), "/");
    }

    public function resolveName( $className ){
        preg_match("/(_|\\\\|^)([^_\\\\]+)$/", $className, $matches);

        return Strings::fromCamelCase( $matches[2], "-" );
    }

}