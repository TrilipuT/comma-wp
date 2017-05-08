<?php

/**
 * Абстрактный клас поля в обьекте fvFieldCollection
 *
 * @author Sancha
 * @version 1.0
 * @since 2011/18/10
 */
abstract class fvField {

    /**
     * Текущее значение поля
     * @var mixed $value
     */
    protected $value;

    /**
     * Значение по умолчнию. Присваевается при создании новой сущности
     * @var mixed $defaultValue
     */
    protected $defaultValue = null;

    /**
     * Возможность быть ничтожеством
     * @var boolean $nullable
     */
    protected $nullable = true;

    /**
     * Возможность быть мультиязычным полем
     */
    protected $languaged = false;

    /**
     * Значения языковых версий поля
     */
    protected $langValues = array( );

    /**
     * Language is used for get and set functions
     * @var Strings $lang
     */
    protected $lang = null;

    /**
     * Is this field is changed
     * @var bool $changed
     */
    protected $changed = [];

    /**
     * Ключ поля
     * @var Strings[a-zA-Z]
     */
    protected $key = null;

    /** Поле уникальное */
    protected $unique = false;

    function __construct( array $fieldSchema, $name ) {
        //var_dump($name);
        $this->key = $name;
        $this->updateSchema( $fieldSchema );
        $this->setDefaultValue();
    }

    function updateSchema( array $fieldSchema ) {
        if ( isset( $fieldSchema[ 'default' ] ) )
            $this->defaultValue = $fieldSchema[ 'default' ];

        if ( isset( $fieldSchema[ 'nullable' ] ) )
            $this->nullable = ( bool ) $fieldSchema[ 'nullable' ];

        if ( isset( $fieldSchema[ 'language' ] ) ){
            $this->languaged = ( bool ) $fieldSchema[ 'language' ];
        }

        if ( isset( $fieldSchema[ 'unique' ] ) )
            $this->unique = ( bool ) $fieldSchema[ 'unique' ];
    }

    function __toString() {
        return $this->asString();
    }

    /**
     * Присваеваем новое значение поля, если конечно сказка завершается счастливым концом
     * @param mixed $value
     */
    public function get() {
        if ( $this->isLanguaged() ) {
            return $this->getLanguageValue( $this->lang );
        }

        return $this->value;
    }

    public function getLanguageValue( $lang ){
        if( $lang ){
            if ( isset( $this->langValues[ $lang ] ) )
                return $this->langValues[ $lang ];
        }

        return $this->defaultValue;
    }

    function isUnique(){
        return $this->unique;
    }

    /**
     * Присваеваем новое значение поля
     * @param mixed $value
     */
    function set( $value ) {
        if ( $this->get() === $value )
            return;

        if ( $this->isLanguaged() ) {
            if ( !$this->lang )
                throw new Field_Exception( "Please define language before set value of field" );

            $this->langValues[ $this->lang ] = $value;
        } else {
            $this->value = $value;
        }

        $this->setChanged(true);
    }

    /**
     * расказывает нам сказку о том, может ли такое значение быть таки правильным
     * с точки зрения логики данного поля
     *
     * @param mixed $newValue
     * @return bool
     */
    public function isValid() {
        if ( is_null( $this->get() ) && !$this->nullable ) {
            return false;
        }
        return true;
    }

    public function setDefaultValue() {
        $this->set( $this->defaultValue );
    }

    public function getDefaultValue() {
        return $this->defaultValue;
    }

    public function isLanguaged() {
        return $this->languaged;
    }

    public function setLanguage( $lang ) {
        $this->lang = $lang;
    }

    public function getLanguage() {
        return $this->lang;
    }

    public function getKey() {
        if ( $this->key )
            return $this->key;

        return get_class( $this );
    }

    function asString() {
        return ( string ) $this->get();
    }

    function asMysql() {
        return $this->get();
    }

    function isChanged() {
        if( $this->isLanguaged() ){
            return $this->changed[ $this->lang ];
        }

        return $this->changed;
    }

    function setChanged( $value ) {
        if( $this->isLanguaged() ){
            $this->changed[ $this->lang ] = ( bool ) $value;
        } else {
            $this->changed = ( bool ) $value;
        }
    }

    public function setErrorType( $val ) {
        $this->errorType = $val;
    }

    public function widget( $type = "base" ){
        $fieldWidget = new fvFieldWidget( $this, $type );
        return $fieldWidget;
    }

    public function isNullable(){
        return $this->nullable;
    }

    public function beforeSave(){}

    public function afterSave(){}
}