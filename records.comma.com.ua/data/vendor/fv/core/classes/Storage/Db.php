<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cah4a
 * Date: 25.06.13
 * Time: 18:57
 * To change this template use File | Settings | File Templates.
 */

class Storage_Db extends fvStorage {

    private $table;
    private $key;

    public function __construct( $tableName, $keyField = 'key'){
        $this->table = $tableName;
        $this->key = $keyField;
    }

    /**
     * @param array $params
     * @return fvStorage|bool must return false if storage can not be worked
     * @throws Exception
     */
    public function create( array $params ){
        if( empty($params['table']) ){
            throw new Exception("Table name not specified");
        }
        if( empty($params['key']) ){
            $params['key'] = 'key';
        }

        return new Storage_Db($params['table'], $params['key']);
    }

    /**
     * @param Strings $key
     * @return array
     */
    public function get( $key ){
        return fvSite::pdo()->getOne("SELECT * FROM {$this->table} WHERE {$this->key} = {$key}");
    }

    public function set( $key, array $values ){
        return fvSite::pdo()->update($this->table, $values, $this->key . " = ", $key);
    }

    public function remove( $key ){
        return fvSite::pdo()->delete($this->table, $this->key . " = ", $key);
    }


}