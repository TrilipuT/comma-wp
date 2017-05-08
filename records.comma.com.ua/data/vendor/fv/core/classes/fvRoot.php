<?php

    /**
     * Base model class
     * @version 2.0
     */
    abstract class fvRoot extends fvFieldCollection implements ArrayAccess{

        protected $_foreign = Array();
        protected $key;
        protected $keyName;
        protected $subclassKeyName;
        protected $tableName;
        protected $valid;
        protected $loadedLanguages = Array();
        protected $implements = Array();

        const UPDATE = 'Update';
        const INSERT = 'Insert';

        public static function make( array $map ){
            return new static( $map );
        }

        function __construct( array $map = array() ){
            $className = call_user_func( array( get_class( $this ), "getEntity" ), array() );
            $this->implement( $className );

            if( !empty( $map ) )
                $this->hydrate( $map );
        }

        public function isImplements( $name ){
            return ( array_search( trim( $name ), $this->implements ) !== false );
        }

        private function implement( $name ){
            $className = call_user_func( array( get_class( $this ), "getEntity" ), array() );

            if( $this->isImplements( $name ) )
            throw new Exception( "Scheme '{$name}' already implemented '{$className}' Entity" );

            $this->implements[] = $name;

            $schema = fvSite::config()->get( "entities.{$name}" );


            if( !$schema )
                $schema = fvSite::config()->get( "abstract.{$name}" );

            if( !$schema )
                throw new Exception( "Can't find implementation '{$name}' of '" . $className . "' Entity" );

            if( is_array( $schema['implements'] ) )
                foreach( $schema['implements'] as $implementSchemaName ){
                    $this->implement( $implementSchemaName );
                }

            if( isset( $schema['extends'] ) ){
                if( ! is_subclass_of( $name, $schema['extends'] ) ){
                    throw new Exception( "Class '{$name}' must extends '" . $schema['extends'] . "' class" );
                }

                $this->implement( $schema['extends'] );
            }

            if( is_array( $schema['foreigns'] ) )
                foreach( $schema['foreigns'] as $key => $foreign ){
                    $foreign['type'] = "foreign" . ( $foreign['type'] ? '_' . ucfirst( $foreign['type'] ) : '' );
                    if( empty($foreign['key']) )
                        $foreign['key'] = lcfirst($foreign['entity']) . "Id";
                    $this->updateFields( array( $foreign['key'] => $foreign ) );
                    $this->_foreign[$key] = $foreign['key'];
                }

            if( is_array( $schema['constraints'] ) ){
                foreach( $schema['constraints'] as $key => $constraint ){
                    $constraint['type'] = "constraint" . ( $constraint['type'] ? '_' . ucfirst( $constraint['type'] )
                        : '' );
                    $constraint['currentEntity'] = $className;
                    $this->updateFields( array( $key => $constraint ) );
                }
            }
            if( is_array( $schema['references'] ) ){
                foreach( $schema['references'] as $key => $reference ){
                    $reference['type'] = "references" . ( $reference['type'] ? '_' . ucfirst( $reference['type'] )
                        : '' );
                    $reference['currentEntity'] = $className;
                    $this->updateFields( array( $key => $reference ) );
                }
            }
            if( is_array( $schema['fields'] ) )
                $this->updateFields( $schema['fields'] );

            if( $schema['table_name'] )
                $this->tableName = $schema['table_name'];
            else
                $this->tableName = Strings::pluralForm(lcfirst( $this->getSuperClass() ));

            if( $schema['primary_key'] )
                $this->keyName = $schema['primary_key'];
            else
                $this->keyName = "id";

            if( $schema['subclass_key'] )
                $this->subclassKeyName = $schema['subclass_key'];
        }

        /**
         * @param $name
         * @return fvField|fvRoot
         */
        public function __get( $name ){
            if( $field = $this->getForeign( $name ) )
                return $field->asEntity();

            return parent::__get( $name );
        }

        public function __set( $name, $value ){
            if( $field = $this->getForeign( $name ) ){
                $field->set($value);
                return;
            }

            parent::__set( $name, $value );
        }

        /** @return Field_Foreign */
        public function getForeign( $name ){
            if( !isset( $this->_foreign[$name] ) )
                return false;

            return parent::__get( $this->_foreign[$name] );
        }

        public function hasForeign( $name ){
            return isset( $this->_foreign[$name] );
        }

        /**
         * @param $name
         * @return bool|Field_Foreign|Field_References|Field_Constraint
         */
        public function getRelation( $name ){
            if( $this->hasForeign($name) )
                return $this->getForeign($name);

            if( ! $this->hasField($name) )
                return false;

            $field = $this->getField($name);

            if(
                $field instanceof Field_References ||
                $field instanceof Field_Constraint
            )
                return $field;

            return false;
        }

        /** @return fvField */
        public function getField( $name ){
            if( isset( $this->_foreign[$name] ) )
                return parent::__get( $this->_foreign[$name] );

            return parent::__get( $name );
        }

        /**
         * @param $valueName
         * @throws Exception
         * @return mixed
         */
        public function getValue( $valueName ){
            $names = explode(".", $valueName);
            $root = $this;
            foreach( $names as $name ){
                if( $root->hasForeign($name) ){
                    $root = $this->getForeign($name)->asEntity();
                    continue;
                }
                if( $root->hasField($name) ){
                    $field = $root->getField($name);
                    if( $field instanceof Field_Constraint || $field instanceof Field_References ){
                        throw new Exception("Constraint and Reference fields doesn't supported for getValue method");
                    }

                    return $root->getField($name)->get();
                }

                if( $root->getPkName() == $name ){
                    return $root->getId();
                }
            }

            if( method_exists($root, "__toString") ){
                return (string)$root;
            }

            throw new Exception("Can't resolve {$this->getEntity()}.{$valueName}");
        }

        function getTableName(){
            return $this->tableName;
        }

        function isNew(){
            return empty( $this->key );
        }

        /**
         * @param $id
         * @return static|null
         */
        public static function find( $id ){
            if( is_array( $id ) ){
                return self::select()->where( $id )->fetchOne();
            }

            return self::getManager()->getByPk( $id );
        }

        /**
         * @param $id
         * @return static|null
         */
        public static function findAll( $where = null, $params = array() ){
            return self::select()->where( $where, $params )->fetchAll();
        }

        /**
         * Return current entity name.
         * Could'n represent class without this function
         * @static
         * @return Strings Entity Name
         */
        static function getEntity(){
            return get_called_class();
        }

        /**
         * Return super entity name.
         *
         * @static
         * @return Strings Entity Name
         */
        static function getSuperClass(){
            $class = call_user_func( [ get_called_class(), "getEntity" ] );

            while( $super = fvSite::config()->get("entities.{$class}.extends") ){
                $class = $super;
            }

            return $class;
        }

        public function getSubclasses()
        {
            $self = $this->getEntity();
            $superClass = $this->getSuperClass();

            return array_filter( array_keys(fvSite::config()->get("entities", [])), function( $class ) use ($superClass, $self ){
                if( ! is_subclass_of( $class, $self ) ){
                    return false;
                }

                return $superClass == call_user_func( [ $class, "getSuperClass" ] );
            });
        }

        /**
         * Это красотень ребятульки! Здесь мы получаем язык, указываем всем полям,
         * какой язык использовать, и записываем значения этих языков
         * @param type $lang
         */
        function setLanguage( $lang ){
            if( !$this->isLanguaged() )
                return;

            if( $lang instanceof Language )
                $lang_code = (string)$lang->code;
            else{
                $lang_code = $lang;
                $lang = Language::getManager()->getOneByCode( $lang_code );
            }

            if( !$lang instanceof Language ){
                throw new Exception( "Language {$lang_code} not found" );
            }

            foreach( $this->_fields as $fieldName => $field ){
                if( $field->isLanguaged() )
                    $field->setLanguage( $lang_code );
            }

            if( !in_array( $lang_code, $this->loadedLanguages ) && !$this->isNew() ){
                $sql = "select *
                    from {$this->getLanguageTableName()}
                    where
                    {$this->getPkName()} = {$this->getPk()} and
                    languageId = {$lang->getId()}
                    limit 1";

                $result = fvSite::pdo()->query( $sql )->fetchAll( PDO::FETCH_ASSOC );

                if( count( $result ) == 1 ){
                    $result = current( $result );

                    foreach( $this->_fields as $fieldName => $field ){
                        if( $field->isLanguaged() ){
                            $field->setLanguage( $lang_code );
                            $map[$fieldName] = $result[$fieldName];
                        }
                    }
                    if( $map ){
                        $this->hydrate( $map );
                    }
                }
                else{
                    $insertList = array( "id" => $this->getId(),
                                         "languageId" => $lang->getId(), );
                    fvSite::pdo()->insert( $this->getLanguageTableName(), $insertList );

                    foreach( $this->_fields as $fieldName => $field ){
                        if( $field->isLanguaged() ){
                            $field->setDefaultValue();
                        }
                    }
                }

                $this->loadedLanguages[] = $lang_code;
            }
            else {
                foreach( $this->_fields as $fieldName => $field ){
                    if( $field->isLanguaged() ){
                        $field->setDefaultValue();
                    }
                }
            }
        }

        function isLanguaged(){
            foreach( $this->_fields as $fieldName => $field ){
                if( $field->isLanguaged() )
                    return true;
            }

            return false;
        }

        function getLanguageTableName(){
            return $this->getTableName() . fvSite::config()->get( "languages.databasePostfix", "Localed" );
        }

        /**
         * Static method that return manager from pool.
         * If manager is missing create EntityManager if exist or create fvRootManager if not exist
         * @return fvRootManager
         */
        public static function getManager(){
            $subclass = get_called_class();
            $class = call_user_func( array( $subclass, "getSuperClass" ), array() );
            return fvManagersPool::get( $class )->setSubclass( $subclass );
        }

        public static function select( $expression = null ){
            return self::getManager()->select( $expression );
        }

        function hydrate( $map, $languaged = false ){
            if( $languaged ){
                if( is_array( $map['main'] ) ){
                    $this->hydrate( $map['main'] );
                }
                if( $this->isLanguaged() ){
                    $languages = Language::getManager()->getAll();
                    foreach( $languages as $lang ){
                        if( is_array( $map[(string)$lang->code] ) ){
                            $this->setLanguage( $lang );
                            $this->hydrate( $map[(string)$lang->code] );
                        }
                    }
                }

                return true;
            }

//            if( get_class( $this ) == "AccountCollector_Yandex" )
//            var_dump( $map, $this->keyName );

            if( isset( $map[$this->keyName] ) ){
                $this->setPk( $map[$this->keyName] );
                unset( $map[$this->keyName] );
            }
            return parent::hydrate( $map );
        }

        function save(){
            if( $this->isNew() ){
                $saveType = self::INSERT;
            }
            else{
                $saveType = self::UPDATE;
            }

            $isTransactionOpen = fvSite::pdo()->isTransactionOpen();
            try{
                if( !$isTransactionOpen )
                    fvSite::pdo()->beginTransaction();

                foreach( $this->getFields() as $field ){
                    if( ! $field->isLanguaged() ){
                        $field->beforeSave();
                    }
                }

                $insertList = array();
                foreach( $this->getFields() as $key => $field ){
                    if( !$field->isLanguaged() && $field->isChanged() )
                        $insertList[$key] = $field->asMysql();
                }

                if( $this->getSubclassKeyName() && $saveType == self::INSERT ){
                    $insertList[$this->getSubclassKeyName()] = get_class( $this );
                }
                if( count( $insertList ) > 0 ){

                    if( $saveType == self::INSERT ){
                        self::getManager()
                            ->insert()
                            ->set( $insertList )
                            ->execute();
                    }
                    else{
                        $where = "{$this->getPkName()} = :{$this->getPkName()}";
                        $whereParams = array( $this->getPkName() => $this->getPk() );

                        self::getManager()
                            ->update()
                            ->set( $insertList )
                            ->where( $where, $whereParams )
                            ->execute();
                    }
                }

                if( $saveType == self::INSERT )
                    $this->setPk( fvSite::pdo()->lastInsertId() );

                if( $this->isLanguaged() ){
                    $languages = Language::getManager()->getAll();

                    foreach( $languages as $lang ){
                        $insertList = array();
                        foreach( $this->getFields() as $key => $field ){
                            if( ! $field->isLanguaged() )
                                continue;
                            $oldLang = $field->getLanguage();
                            $field->setLanguage( $lang->code->get() );
                            $field->beforeSave();
                            if( $field->isChanged() )
                                $insertList[$key] = $field->asMysql();
                            $field->setLanguage( $oldLang );
                        }

                        if( count( $insertList ) > 0 ){
                            if( $saveType == self::INSERT ){
                                $tInsertList = $insertList;
                                $tInsertList['id'] = $this->getPk();
                                $tInsertList['languageId'] = $lang->getPk();
                                fvSite::pdo()->insert( $this->getLanguageTableName(), $tInsertList );
                            }
                            if( $saveType == self::UPDATE ){
                                $whereParams = array( $this->getPkName() => $this->getPk(),
                                                      "languageId"       => $lang->getPk() );
                                $where = array( "{$this->getPkName()} = :{$this->getPkName()} AND languageId = :languageId" );
                                fvSite::pdo()->update( $this->getLanguageTableName(),
                                                      $insertList,
                                                      $where,
                                                      $whereParams );
                            }
                        }
                    }
                }

                foreach( $this->getFields() as $field ){
                    $field->afterSave();
                }

                $this->setChanged( false );

                if( !$isTransactionOpen )
                    fvSite::pdo()->commit();

                return true;
            }
            catch( Exception $e ){
                if( !$isTransactionOpen )
                    fvSite::pdo()->rollBack();

                throw $e;
            }
        }

        function delete(){
            if( $this->isNew() )
                return false;

            foreach( $this->getFields() as $key => $field ){
                if( $field instanceof Field_String_File )
                    $field->delete();
            }

            $where = array( "{$this->getPkName()} = :{$this->getPkName()}" );
            $whereParams = array( $this->getPkName() => $this->getPk() );
            fvSite::pdo()->delete( $this->getTableName(), $where, $whereParams );

            return true;
        }

        function isValid(){
            $className = call_user_func( array( get_class( $this ), "getEntity" ), array() );
            if( !parent::isValid() )
                return false;

            foreach( $this->getFields() as $key => $field ){
                if( $field->isUnique() ){
                    $value = $field->asMysql();

                    if( is_null( $value ) )
                        continue;

                    if( $this->isNew() )
                        $count = fvManagersPool::get( $className )->getCount( "{$key} = :k",
                                                                              array( 'k' => $value ) );
                    else
                        $count = fvManagersPool::get( $className )
                            ->getCount( "{$key} = :k and root.{$this->getPkName()} <> :pk",
                                        array( 'k' => $value, 'pk' => $this->getPk() ) );

                    if( $count > 0 )
                        return false;
                }
            }

            return true;
        }

        function getPk( $keyName = null ){
            if( is_null( $keyName ) )
                return $this->key;
            else
                return $this->key[$keyName];
        }

        function setPk( $key, $keyName = null ){
            if( is_null( $keyName ) && !is_array( $this->key ) )
                $this->key = $key;
            else{
                $this->key[$keyName] = $key;
            }

            foreach( $this->getFields() as $field ){
                if( method_exists( $field, "setRootPk" ) )
                    $field->setRootPk( $key );
            }

            return $field;
        }

        function getPkName(){
            return $this->keyName;
        }

        function getSubclassKeyName(){
            return $this->subclassKeyName;
        }

        function offsetExists( $fieldName ){
            return $this->hasField( $fieldName ) || $this->hasForeign($fieldName);
        }

        function offsetGet( $fieldName ){
            return $this->__get( $fieldName, null );
        }

        function offsetUnset( $fieldName ){
            return $this->removeField( $fieldName );
        }

        function offsetSet( $fieldName, $newValue ){
            if( $this->hasField( $fieldName ) )
                return $this->set( $fieldName, $newValue );
            else
                $this->addField( $fieldName, ( $newValue ) ? gettype( $newValue ) : 'Strings', $newValue );
        }

        function unQuote( $mVal ){
            $mVal = @is_array( $mVal ) ? array_map( "UnQuote", $mVal )
                : ( isset( $mVal ) ? stripslashes( $mVal ) : null );
            return $mVal;
        }

        /**
         * Привет, я костыль!
         * @return Strings ровным счётом ничего.
         */
        public function getLangVersion(){
            return "";
        }

        function widget( $templateName = "base", array $vars = array() ){
            if( is_array($templateName) && empty( $vars ) ){
                $vars = $templateName;
                $templateName = 'base';
            }
            $widget = new fvWidget( $this, $templateName );
            $vars['entity'] = $this;
            $widget->assignParams( $vars );
            return $widget;
        }

        public function getId(){
            return $this->getPk();
        }

        function toHash(){
            return array_merge( array( $this->getPkName() => $this->getPk() ), parent::toHash() );
        }

    }