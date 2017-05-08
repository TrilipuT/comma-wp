<?php

namespace AdminBundle\Controller;

use DbGenerator;
use fvMultiController;
use fvSite;

class Db extends fvMultiController
{

    /**
     * @route /db
     *
     * @option menu.name Разработка // 1 // Генерация базы
     * @option menu.icon database
     * @option acl db
     */
    function dbAction()
    {
        $entities = array_keys( fvSite::config()->get( "entities" ) );
        $this->view()->entities = array_filter( $entities, function ( $entity ){
            if( ! class_exists( $entity ) ){
                return false;
            }

            return call_user_func( [ $entity, "getSuperClass" ] ) == $entity;
        } );
    }

    /**
     * @route /db/generate
     * @option acl db
     */
    function generateAction( $entities, $perform = false )
    {
        if( $perform ){
            foreach( $entities as $entity ){
                $generator = new DbGenerator($entity);
                $generator->perform();
            }
            return $this->redirect( "db" );
        }

        $actions = [ ];
        foreach( $entities as $entity ){
            $generator = new DbGenerator($entity);
            $actions[$entity] = $generator->getCommands();
        }
        $this->view()->actions = $actions;
    }

    function status( $entity )
    {
        $generator = new DbGenerator($entity);
        switch( $generator->getStatus() ){
            case DbGenerator::STATUS_NOT_EXIST:
                return "<i class='fa fa-times color-red'></i>";
            case DbGenerator::STATUS_EXIST:
                return "<i class='fa fa-refresh'></i>";
            case DbGenerator::STATUS_EXACT:
                return "<i class='fa fa-check color-green'></i>";
        }

        return "unknown";
    }

    public function highlight( $code )
    {
        $words = [
            "create",
            "select",
            "from",
            "table",
            "engine",
            "default",
            "primary",
            "key",
            "unique",
            "foreign",
            "references",
            "constraint",
            "alter",
            "drop",
            "column",
            "charset",
            "add",
            "change",
            "on",
            "delete",
            "update",
        ];
        $code = preg_replace( "/\\b(" . implode( "|", $words ) . ")\\b/ui", "<span class='color-blue'>\\0</span>", $code );

        $words = [
            "null",
            "not null",
        ];
        $code = preg_replace( "/\\b(" . implode( "|", $words ) . ")\\b/ui", "<span class='color-orange'>\\0</span>", $code );

        $code = preg_replace_callback( "/`[^`]*`/i", function ( $m ){
            return "<span class='color-green'>" . strip_tags( $m[0] ) . "</span>";
        }, $code );

        return $code;
    }

}