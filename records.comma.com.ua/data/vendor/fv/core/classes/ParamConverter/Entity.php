<?php
/**
 * Created by cah4a.
 * Time: 10:02
 * Date: 05.10.14
 */

class ParamConverter_Entity extends fvParamConverter {

    private $entityName;
    private $field;

    function __construct( $entityName, $field = null )
    {
        $this->entityName = $entityName;
        $this->field = $field;

        if( is_null($field) ){
            $this->field = $this->getManager()->getRootObj()->getPkName();
        }
    }

    function get( $value )
    {
        $entity = $this->getManager()->select()->where([ $this->field => $value ])->fetchOne();

        if( $entity instanceof fvRoot ){
            return $entity;
        }

        return null;
    }

    function generate( $value )
    {
        $rootObj = $this->getManager()->getRootObj();

        if( ! $value instanceof $rootObj ){
            throw new Exception("Param {$this->getName()} must be instance of {$this->entityName}");
        }

        return $value->getValue( $this->field );
    }

    /**
     * @return fvRootManager
     * @throws Exception
     */
    private function getManager(){
        return fvManagersPool::get( $this->entityName );
    }

} 