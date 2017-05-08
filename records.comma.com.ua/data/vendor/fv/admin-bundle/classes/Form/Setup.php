<?php

namespace AdminBundle\Form;

use Admin;
use Config_YmlCreator;
use DbGenerator;
use Error_PageNotFound;
use Exception;
use Form_Field_String;
use fvPDO;
use fvSite;
use PDOException;

class Setup extends Base
{

    private $setupDatabase = false;

    function __construct()
    {
        if( ! fvSite::config()->get( "debug" ) ){
            throw new Error_PageNotFound;
        }

        try {
            fvSite::pdo();
        } catch (Exception $e) {
            $this->setupDatabase = true;
            $this->addFields( array(
                "dbHost" => new Form_Field_String("text", array( 'required' )),
                "dbUser" => new Form_Field_String("text", array( 'required' )),
                "dbPass" => new Form_Field_String("password"),
                "dbName" => new Form_Field_String("text", array( 'required' )),
            ) );

            $this->getField( "dbHost" )->setValue( "localhost" );
            $this->getField( "dbUser" )->setValue( "root" );
        }

        $this->addFields( array(
            "adminLogin" => new Form_Field_String("text", array( 'required' )),
            "adminPassword" => new Form_Field_String("password", array( 'required' )),
        ) );

        $this->getField( "adminLogin" )->setValue( "admin" );
    }

    public function validate()
    {
        parent::validate();

        if( ! $this->isValid() ){
            return $this;
        }

        if( ! $this->setupDatabase ){
            return $this;
        }

        try {
            $dsn = "mysql:host={$this->dbHost};dbname={$this->dbName};charset=utf8";
            new fvPDO($dsn, $this->dbUser, $this->dbPass);
        } catch (PDOException $e) {

            switch( $e->getCode() ){
                case 1044:
                    $this->getField( "dbUser" )->setValidationMessage( "accessDenied" );
                    $this->getField( "dbPass" )->setValidationMessage( "accessDenied" );
                    break;
                case 1049:
                    $this->getField( "dbName" )->setValidationMessage( "databaseNotFound" );
                    break;
                default:
                    $this->getField( "dbHost" )->setValidationMessage( "unknownDatabaseError" );
            }

            return false;
        }

        return $this;
    }

    protected function process()
    {
        if( $this->setupDatabase ){
            $this->createConfigs();
        }
        $this->upDatabase();
        $this->createAdmin();
        return true;
    }

    private function createAdmin()
    {
        $admin = new Admin();
        $admin->login = $this->adminLogin;
        $admin->password = $this->adminPassword;
        $admin->role = 'master';
        $admin->save();
    }

    private function upDatabase()
    {
        $generator = new DbGenerator("Admin");
        $generator->perform();
    }

    private function createConfigs()
    {
        if( ! file_exists( "configs/servers" ) ){
            mkdir( "configs/servers" );
            chmod( "configs/servers", 0777 );
        }

        $folder = "configs/servers/" . gethostname();
        mkdir( $folder );

        Config_YmlCreator::make( array(
            "database" => array(
                "dsn" => "mysql:host={$this->dbHost};dbname={$this->dbName};charset=utf8",
                "user" => $this->dbUser,
                "pass" => $this->dbPass,
            )
        ) )->saveToFile( $folder . "/database.yml" );

        Config_YmlCreator::make( array(
            "debug" => false
        ) )->saveToFile( $folder . "/app.yml" );

        fvSite::config()->load( $folder . "/database.yml" );
    }
}