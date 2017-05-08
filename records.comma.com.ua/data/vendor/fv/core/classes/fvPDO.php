<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Dmitry Kukarev
 * Date: 18.04.12
 * Time: 20:02
 */
class fvPDO extends Pdo
{
    protected $hasActiveTransaction = false;

    function __construct($dsn, $user, $pass, $params = array()) {
        $default = array(
            PDO::ATTR_TIMEOUT => "2",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8',
        );

        foreach( $default as $key => $value ){
            if( !isset($params[$key]) )
                $params[$key] = $value;
        }

        // Глушим warning'и, потому что даже если стоит PDO::ERRMODE_EXCEPTION,
        // если база не досутпна, PDO кидает warning'ы
        @parent::__construct($dsn, $user, $pass, $params);
    }

    function beginTransaction () {
        $this->hasActiveTransaction = parent::beginTransaction ();
        return $this->hasActiveTransaction;
    }

    function commit () {
        parent::commit ();
        $this->hasActiveTransaction = false;
    }

    function transaction( Closure $closure ){
        $isTransactionOpen = fvSite::pdo()->isTransactionOpen();
        try {
            if( ! $isTransactionOpen ){
                $this->beginTransaction();
            }

            $result = $closure();

            if( ! $isTransactionOpen ){
                $this->commit();
            }

            return $result;
        } catch ( Exception $e ){
            if( ! $isTransactionOpen ){
                $this->rollback();
            }

            throw $e;
        }
    }

    function rollback () {
        parent::rollback ();
        $this->hasActiveTransaction = false;
    }

    public function isTransactionOpen(){
        return $this->hasActiveTransaction;
    }

    public function update($tableName, $updateValues, $where, $whereParams) {
        //$updateValues = self::prepareSetParams(array_merge($updateValues));
        $updateString = '';
        foreach ($updateValues as $field=>$value) {
            $updateString .= "`$field` = :$field, ";
        }
        $updateString = substr($updateString, 0, -2);

        if( is_array($where) )
            $where = implode( " AND ", $where );

        $sql = "UPDATE `{$tableName}` SET $updateString WHERE " . $where;

        if( !is_array($whereParams) )
            $whereParams = array($whereParams);

        return $this->queryPrepared($sql, array_merge($updateValues, $whereParams));
    }

    public function insert($tableName, $values) {
        $sql = "INSERT INTO `{$tableName}` (".implode(self::sanitizeFieldNames(array_keys($values)), ',').") VALUES (:".implode(array_keys($values), ',:').")";
        $this->queryPrepared($sql, $values);
    }

    public function delete($tableName, $where, $whereParams) {
        if( ! is_array($whereParams) )
            $whereParams = array($whereParams);

        $sql = "DELETE FROM `{$tableName}` WHERE " . implode( " AND ", $where );
        return $this->queryPrepared($sql, $whereParams);
    }

    public function getAssoc($sql, $fetchType = PDO::FETCH_ASSOC) {
        return parent::query($sql)->fetchAll($fetchType);
    }

    public function getOne($sql) {
        return parent::query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchOne( $tableName, $where, $whereParams ){
        return $this
            ->prepare( "SELECT * FROM {$tableName} WHERE {$where} LIMIT 1" )
            ->fetch(PDO::FETCH_ASSOC);
    }

    public function queryPrepared($sql, $params) {
        return parent::prepare($sql)->execute( self::prepareSetParams($params));
    }

    public static function prepareSetParams($params) {
        $result = array();
        foreach( $params as $field => $value ){
            $key = ":" . preg_replace( "/[^\d\w]/", "", $field );
            $result[$key] = $value;
        }
        return $result;
    }

    public static function sanitizeFieldNames($names) {
        if (is_array($names)) {
            foreach( $names as &$name ) {
                $name = "`$name`";
            }
            return $names;
        } else {
            return "`$names`";
        }
    }
}
