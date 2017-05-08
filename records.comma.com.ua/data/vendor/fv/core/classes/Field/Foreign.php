<?php

class Field_Foreign extends Field_Int
{

    protected $entityName;
    protected $where;
    protected $key;
    protected $delete;
    protected $update;

    const RULE_CASCADE = 1;
    const RULE_RESTRICT = 2;
    const RULE_SET_NULL = 3;
    const RULE_NO_ACTION = 4;

    static $cache = array();

    function __construct( array $fieldSchema, $key )
    {
        parent::__construct( $fieldSchema, $key );

        if( ! isset($fieldSchema['entity']) ){
            throw new Exception("You should specify entity for foreign {$key}");
        }
        $this->entityName = $fieldSchema['entity'];

        if( isset($fieldSchema['where']) ){
            $this->where = $fieldSchema['where'];
        }

        if( ! isset($fieldSchema['delete']) ){
            $fieldSchema['delete'] = "cascade";
        }

        switch( $fieldSchema['delete'] ){
            case "restrict":
                $this->delete = self::RULE_RESTRICT;
                break;
            case "setnull":
                $this->delete = self::RULE_SET_NULL;
                break;
            case "noaction":
                $this->delete = self::RULE_NO_ACTION;
                break;
            default:
                $this->delete = self::RULE_CASCADE;
        }

        if( ! isset($fieldSchema['update']) ){
            $fieldSchema['update'] = "cascade";
        }

        switch( $fieldSchema['update'] ){
            case "restrict":
                $this->update = self::RULE_RESTRICT;
                break;
            case "setnull":
                $this->update = self::RULE_SET_NULL;
                break;
            case "noaction":
                $this->update = self::RULE_NO_ACTION;
                break;
            default:
                $this->update = self::RULE_CASCADE;
        }

        if( ! isset($fieldSchema['nullable']) ){
            $this->nullable = false;
        }
    }

    function asMysql()
    {
        if( ! $this->get() ){
            return null;
        }

        return $this->get();
    }

    function asEntity()
    {
        if( ! isset(self::$cache[$this->entityName][$this->get()]) ){
            self::$cache[$this->entityName][$this->get()] = fvManagersPool::get( $this->entityName )->getByPk( $this->get() );
        }

        return self::$cache[$this->entityName][$this->get()];
    }

    function getForeignEntityName()
    {
        return $this->entityName;
    }

    static function preloadCache( $entityName, $entities = array() )
    {
        if( isset(self::$cache[$entityName]) ){
            self::$cache[$entityName] = array_replace( $entities, self::$cache[$entityName] );
        }
        else {
            self::$cache[$entityName] = $entities;
        }
    }

    public function getEntityName()
    {
        return $this->entityName;
    }

    static function clearCache()
    {
        self::$cache = array();
    }

    function getForeignEntityTableName()
    {
        /** @var fvRoot $entity */
        $entity = new $this->entityName;
        return $entity->getTableName();
    }

    function getForeignEntityPkName()
    {
        /** @var fvRoot $entity */
        $entity = new $this->entityName;
        return $entity->getPkName();
    }

    function set( $value )
    {
        if( is_object( $value ) ){
            /** @var $value fvRoot */
            if( ! $value instanceof $this->entityName ){
                $givenClass = get_class( $value );
                throw new Error_Field_Verbose("Instance of '{$this->entityName}' expected '{$givenClass}' given.");
            }

            if( $value->isNew() ){
                throw new Error_Field_Verbose("Cannot verbose non saved object!");
            }

            parent::set( $value->getPk() );
        }
        else {
            parent::set( $value );
        }
    }

    public function getOnDelete(){
        return $this->delete;
    }

    public function getOnUpdate(){
        return $this->update;
    }

}