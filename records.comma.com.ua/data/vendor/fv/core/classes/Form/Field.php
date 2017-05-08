<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cah4a
 * Date: 20.06.13
 * Time: 14:09
 * To change this template use File | Settings | File Templates.
 */

abstract class Form_Field extends fvComponent {

    /** @var mixed */
    private $value = null;

    /** @var Strings */
    private $key = null;

    /** @var Strings */
    private $validationMessage = null;

    /** @var bool  */
    private $hint = false;

    /** @var fvForm */
    protected $form;

    /** @var Form_FieldValidator[] */
    private $validators = array();

    /** @var Form_FieldValidator[] */
    private $invalidValidators = array();

    /** @var bool */
    private $disabled = false;

    /** @var Form_FieldContainer */
    private $container;

    public static function make(){
        $args = func_get_args();

        if( empty($args) ){
            return new static();
        }

        $class = new ReflectionClass( get_called_class() );
        return $class->newInstanceArgs( $args );
    }

    function getComponentName() {
        return "form/field";
    }

    /**
     * @param $validator Form_FieldValidator|Strings
     *
     * @return $this
     * @throws Error_Form
     */
    public function addValidator( $validator ){
        if( is_string($validator) ){
            $validator = "Form_FieldValidator_" . ucfirst($validator);
            $validator = new $validator;
        }

        if( ! $validator instanceof Form_FieldValidator )
            throw new Error_Form("Validator must be instance of Form_FieldValidator. Given " . get_class($validator));

        $this->validators[] = $validator;
        return $this;
    }

    /**
     * @param $validators Form_FieldValidator[]|Strings[]
     *
     * @return $this
     */
    public function addValidators( $validators ){
        foreach( $validators as $validator ){
            $this->addValidator($validator);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function validate () {
        $this->invalidValidators = array();
        foreach( $this->getValidators() as $validator ){
            if( ! $validator->validate( $this ) ){
                $this->invalidValidators[] = $validator;
            }
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(){
        if( !empty($this->validationMessage) )
            return false;

        return empty($this->invalidValidators);
    }

    /**
     * @return bool
     */
    public function isRequired(){
        return !! $this->getValidator("required");
    }

    public function setValue( $value ) {
        $this->value = $value;
        return $this;
    }

    public function getValue(){
        return $this->value;
    }

    public function setForm( fvForm $form, Form_FieldContainer $container, $key ){
        $this->key = (string)$key;
        $this->form = $form;
        $this->container = $container;

        return $this;
    }

    /**
     * @return fvForm
     */
    public function getForm(){
        return $this->form;
    }

    /**
     * @return Strings
     */
    public function getId(){
        $id = preg_replace( '/\[|\]/', '_', trim($this->getName(), "[]") );

	    return Strings::toCamelCase( $id );
    }

    /**
     * @param Strings $container
     *
     * @return $this
     */
    public function setContainer ($container) {
        $this->container = $container;
        return $this;
    }

    /**
     * @return Form_FieldContainer
     */
    public function getContainer(){
        return $this->container;
    }

	/**
	 * @return Strings
     */
    public function getName(){
        return "{$this->container->getContainerName()}[{$this->getKey()}]";
    }

	/**
	 * @return Strings
     */
    public function getLabel(){
        $label = $this->getForm()->getName() . ".fields." . $this->getKey();
        $defaultLabel = "defaults.fields." . $this->getKey();
        $dictionary = $this->getForm()->getDictionary();

        if( $dictionary->hasTranslate( $defaultLabel ) && ! $dictionary->hasTranslate( $label ) ){
            return $dictionary->translate( $defaultLabel );
        }

        return $dictionary->translate( $label );
    }

    public function getKey(){
        return $this->key;
    }

	/**
	 * @param Strings|null $type
     * @return Form_FieldValidator[]
     */
    public function getValidators( $type = null ) {
        if( $type ){
            if( $type[0] != "\\" ){
                $type = "Form_FieldValidator_" . ucfirst($type);
            }

            $result = array();
            foreach( $this->getValidators() as $validator ){
                if( $validator instanceof $type ){
                    $result[] = $validator;
                }
            }

            return $result;
        }

        return $this->validators;
    }

    /**
     * @return null|Strings
     */
    public function getValidationMessage(){
        if( !empty($this->validationMessage) ){
            $message = $this->validationMessage;
        } else {
            if( $this->isValid() )
                return null;

            $message = lcfirst( preg_replace( '/^Form_FieldValidator_/', '', get_class( reset($this->invalidValidators) )));
        }

        $key = $this->getForm()->getName() . ".validators." . $message;
        $defaultKey = "defaults.validators." . $message;
        $dictionary = $this->getForm()->getDictionary();

        if( $dictionary->hasTranslate( $defaultKey ) && ! $dictionary->hasTranslate( $key ) ){
            return $dictionary->translate( $defaultKey );
        }

        return $dictionary->translate( $key );
    }

    public function getValidator( $type ){
        $validators = $this->getValidators( $type );

        if( empty($validators) )
            return null;

        return reset($validators);
    }

    public function setValidationMessage( $message ){
        $this->validationMessage = $message;
    }

    public function showHint( $enabled = true ){
        $this->hint = $enabled;
        return $this;
    }

    public function getHint(){
        $message = $this->getForm()->getName() . ".hints." . $this->key;
        return $this->getForm()->getDictionary()->translate( $message );
    }

    public function isHintShowed(){
        return $this->hint;
    }

    public function disabled( $disabled = true ){
        $this->disabled = $disabled;
        return $this;
    }

    public function isDisabled(){
        return $this->disabled;
    }

}