<?php

/**
 *
 */
class fvRootManager {

    /** @var fvRoot */
    protected $rootObj = null;
    /** @var fvRoot[] */
    protected $rootSubclassesObjs = array();
    protected $modifier = array();
    static $instace = null;
    private $useQmodifier = true;
    private $queryObj = false;

    private $subclass;

    const GET_EQUAL = '=';
    const GET_NOT_EQUAL = '<>';
    const GET_GT = '>';
    const GET_GTE = '>=';
    const GET_LT = '<';
    const GET_LTE = '<=';
    const GET_LIKE = 'LIKE';
    const GET_NOT_LIKE = 'NOT LIKE';

    const GET_CHECK_CASE = 'cc';
    const GET_UNCHECK_CASE = 'ucc';

    public function __construct( $entity ){
        $this->rootObj = new $entity;
        if( fvSite::config()->get( "qModifier" ) ){
            foreach( fvSite::config()->get( "qModifier" ) as $iEntity => $modificators ){
                if( $this->rootObj->isImplements( $iEntity ) ){
                    foreach( $modificators as $type => $modifier ){
                        $this->modifier[$type][] = $modifier;
                    }
                }
            }
        }
    }

    /**
     * return entity created from
     * @return fvRoot
     */
    public function getEntity(){
        return $this->rootObj;
    }

    /** @return fvQuery */
    public function select( $expression = null ){
        return $this->query()->select( $expression );
    }

    public function query( $alias = 'root' ){
        $query = new fvQuery($this, $alias);
        $query->useQModifiers( $this->useQmodifier );

        if( $this->getSubclass() && $this->getSubclass() != get_class( $this->rootObj ) && $this->rootObj->getSubclassKeyName()
        ){
            $str = "{$alias}.{$this->rootObj->getSubclassKeyName()} = '{$this->getSubclass()}'";
            $query->addQModifier( 'where', $str );
        }

        return $query;
    }

    /** @return fvQuery */
    public function insert(){
        return $this->query()->insert();
    }

    /** @return fvQuery */
    public function update(){
        return $this->query()->update();
    }

    /** @return fvQuery */
    public function delete(){
        return $this->query()->delete();
    }

    /**
     * Получить по первичному ключу
     *
     * @param mixed $pk
     * @param mixed $createNonExist : необходимо ли создание, если не существует
     * @return fvRoot - $this->rootObj
     */
    public function getByPk( $pk, $createNonExist = false ){
        if( $object = $this->select()->where( "root.{$this->rootObj->getPkName()} = ?", (int)$pk )->fetchOne() ){
            return $object;
        }

        if( $createNonExist ){
            return clone $this->rootObj;
        }

        return false;
    }

    /**
     * @param array $map
     * @return fvRoot
     */
    public function instantiate( array $map = array() ){
        $subclassKey = $this->rootObj->getSubclassKeyName();
        if( $subclassKey ){
            $subclass = $map[$subclassKey];

            if( is_null( $subclass ) ){
                $rootObj = clone $this->rootObj;
            }
            else {
                if( ! isset($this->rootSubclassesObjs[$subclass]) ){
                    $this->rootSubclassesObjs[$subclass] = new $subclass;
                }

                $rootObj = clone $this->rootSubclassesObjs[$subclass];
            }
        }
        else {
            $rootObj = clone $this->rootObj;
        }

        if( $rootObj->isLanguaged() ){
            if( isset($map['languageId']) ){
                $rootObj->setLanguage( Language::getManager()->getByPk( $map['languageId'] ) );
                unset($map['languageId']);
            }
            else {
                $rootObj->setLanguage( Language::getManager()->getCurrentLanguage() );
            }
        }

        $rootObj->hydrate( $map );
        $rootObj->setChanged( false );

        return $rootObj;
    }

    /**
     * @returns fvRoot[]
     */
    function getAll(){
        return $this->select()->fetchAll();
    }

    /**
     * returns exactly one row or returns false
     *
     * @param Strings $where
     * @param Strings $order
     * @param array $params
     *
     * @return fvRoot|null
     */
    function getOne( $where = "", $order = "", $params = null ){
        return $this
               ->select()
               ->where( $where, $params )
               ->orderBy( $order )
               ->fetchOne();
    }

    /**
     * returns exactly one instance of fvRoot
     *
     * @param Strings $where
     * @param Strings $order
     * @param Array $params
     *
     * @return fvRoot
     */
    function getOneInstance( $where = "", $order = "", $params = null ){
        $instance = $this->getOne( $where, $order, $params );
        if( $instance instanceof fvRoot ){
            return $instance;
        }
        throw new EInstanceError("Instance '{$this->rootObj->getEntity()}' is not exists");
    }

    /**
     * @param array $ids of Projects or Primary Key values
     * @return array|Project
     */
    function getByIds( array $ids ){
        if( count( $ids ) == 0 ){
            return array();
        }

        if( $ids[0] instanceof $this->rootObj ){
            return $ids;
        } else {
            return $this
                   ->select()
                   ->whereIn( 'root.' . $this->rootObj->getPkName(), $ids )
                   ->fetchAll();
        }
    }

    function recall( $ids, $functionName, array $params = array() ){
        if( is_array( $functionName ) ){
            $functions = $functionName;
        }
        else {
            $functions = array( $functionName );
        }

        foreach( $this->getByIds( $ids ) as $entity ){
            foreach( $functions as $functionName ){
                if( ! method_exists( $entity, $functionName ) ){
                    throw new Exception('unknown action!');
                }

                call_user_func_array( array( $entity, $functionName ), $params );
            }
            $entity->save();
        }
    }

    function qWhereModifier( $where ){
        if( empty($this->modifier['where']) ){
            return $where;
        }

        if( empty($where) ){
            return implode( ' AND ', $this->modifier['where'] );
        }

        return $where . ' AND (' . implode( ' AND ', $this->modifier['where'] ) . ')';
    }

    function qOrderModifier( $order ){
        if( empty($this->modifier['order']) ){
            return $order;
        }

        if( empty($order) ){
            return implode( ' AND ', $this->modifier['order'] );
        }

        return $order . ', ' . implode( ', ', $this->modifier['order'] );
    }

    /**
     * Возвращает количество сущностей удовлетворяющих условию либо false в случае неудачи
     *
     * @param $where Strings условие
     * @param $params array|mixed параметры условия
     *
     * @return bool|mixed
     * @throws Exception
     */
    public function getCount( $where = null, $params = null ){
        $query = new fvQuery($this);
        $query->useQModifiers( $this->useQmodifier );

        if( ! empty($where) ){
            $query->where( $where, $params );
        }

        return $query->getCount();
    }

    public function __call( $name, $arguments ){
        if( strpos( $name, 'getBy' ) === 0 ){
            if( ($fieldName = $this->checkName( substr( $name, 5 ) )) === false ){
                throw new Exception("Unrecognized field '" . substr( $name, 5 ) . "'");
            }
        }
        elseif( strpos( $name, 'getOneBy' ) === 0 ) {
            if( ($fieldName = $this->checkName( substr( $name, 8 ) )) === false ){
                throw new Exception("Unrecognized field '" . substr( $name, 8 ) . "'");
            }
        }
        else {
            throw new Exception("Call to undefined function");
        }

        $value = $arguments[0];
        $condition = (! empty($arguments[1])) ? $arguments[1] : self::GET_EQUAL;
        $case_sensitive = (! empty($arguments[2])) ? ($arguments[2] == self::GET_UNCHECK_CASE) : true;

        if( strpos( $name, 'getBy' ) === 0 ){
            return $this->getAllByFieldName( $fieldName, $value, $condition, null, $case_sensitive );
        }
        else {
            $object = $this->getAllByFieldName( $fieldName, $value, $condition, "1", $case_sensitive );
            return reset( $object );
        }
    }

    protected function getAllByFieldName( $fieldName, $value, $condition, $limit = null, $case_sensitive = true ){
        if( $case_sensitive ){
            $where = "{$fieldName} {$condition} :value";
        }
        else {
            $where = "UPPER({$fieldName}) {$condition} :value";
        }

        return $this->select()->andWhere( $where, array( 'value' => $value ) )->limit( $limit )->fetchAll();
    }

    protected function checkName( $name ){
        if( $this->rootObj->hasField( $name ) ){
            return $name;
        }

        $name = lcfirst( $name );
        if( $this->rootObj->hasField( $name ) ){
	        return $name;
        }

        $name = Strings::fromCamelCase( $name );
        if( $this->rootObj->hasField( $name ) ){
            return $name;
        }
        return false;
    }

    /**
     * @deprecated Please use update() fvQuery syntax
     *
     * @param $where
     * @param $updateFields
     *
     * @return bool
     */
    public function massUpdate( $where, $updateFields ){
        $o = clone $this->rootObj;

        foreach( $updateFields as $field => $value ){
            $fieldObj = $o->$field;
            $fieldObj->set( $value );
        }

        $values = array();
        foreach( $o->getFields() as $fieldName => $field ){
            if( $field->isChanged() ){
                $values[$fieldName] = $field->asMysql();
            }
        }

        $this->update()->where( $where )->set( $values )->execute();

        return true;
    }

    public function getObjectBySQL( $sql, $addField = array(), $single_object = false ){
        $data = fvSite::pdo()->getAssoc( $sql );
        $res = array();
        foreach( $data as $k => $v ){
            $ex = new $this->rootObj;
            if( count( $addField ) ){
                foreach( $addField as $key => $val ){
                    $ex->addField( $key, "$val", "" );
                }
            }
            $ex->hydrate( $v );
            $res[] = $ex;
        }
        if( $single_object ){
            if( isset($res[0]) ){
                return $res[0];
            }
            else {
                return array();
            }
        }
        else {
            return $res;
        }
    }

    function getTableName(){
        return $this->rootObj->getTableName();
    }

    function getLanguageTableName(){
        return $this->rootObj->getLanguageTableName();
    }

    /**
     * @return \fvRoot|null
     */
    public function getRootObj(){
        return $this->rootObj;
    }

    public function useQmodifiers( $bool = true ){
        $this->useQmodifier = (boolean)$bool;
        return $this;
    }

    public function setSubclass( $subclass ){
        $this->subclass = $subclass;
        return $this;
    }

    public function getSubclass(){
        return $this->subclass;
    }

}
