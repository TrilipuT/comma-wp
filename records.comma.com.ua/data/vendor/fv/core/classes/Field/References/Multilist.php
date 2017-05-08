<?php

/**
 * Created by JetBrains PhpStorm.
 * User: cah4a
 * Date: 16.05.12
 * Time: 19:55
 */
class Field_References_Multilist extends Field_References
{

    function asArray()
    {
        if( empty($this->pk) ){
            return array();
        }

        return fvSite::pdo()->query( "SELECT {$this->foreignEntityKey} FROM {$this->refTableName} WHERE {$this->currentEntityKey} = {$this->pk}" )->fetchAll( PDO::FETCH_COLUMN );
    }

    function getList( fvRoot $entity )
    {
        $manager = fvManagersPool::get( $this->foreignEntity );
        return $manager->select()->aggregateBy( $manager->getRootObj()->getPkName() )->execute();
    }

}
