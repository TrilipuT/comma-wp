<?php

abstract class fvForm extends fvComponent implements Form_FieldContainer {

    private $processed = false;
    /** @var fvRequest */
    private $request;
    /** @var Form_Field[] */
    private $fields = array();
    private $isValid = null;
    private $container = null;
    private $submitUrl = "";

    /** @var fvDictionary */
    private $dictionary;
    private $name;

    final public function getComponentName() {
        return "form";
    }

    /**
     * @param $key
     * @return Form_Field
     * @throws Error_Form_Field
     */
    public function getField( $key ){
        if( isset($this->fields[$key]) )
            return $this->fields[$key];

        throw new Error_Form_Field("Field {$key} is not defined");
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasField( $key ){
        return isset($this->fields[$key]);
    }

    /**
     * @param Strings $key
     * @param Form_Field $field
     *
     * @return $this
     * @throws Error_Form_Field
     */
    final public function addField( $key, Form_Field $field ){
        if( isset($this->fields[$key]) )
            throw new Error_Form_Field("Field {$key} already defined");

        $this->fields[$key] = $field;
        $field->setForm( $this, $this, $key );
        return $this;
    }

    public function assignVars(){
        $this->view()->fields = $this->fields;
    }

    /**
     * @param string Form_Field[] $fields
     * @return $this
     * @throws Error_Form_Field
     */
    final public function addFields( array $fields ){
        foreach( $fields as $key => $field ){
            $this->addField( $key, $field );
        }

        return $this;
    }

    /**
     * @param Strings $key
     *
     * @return $this
     * @throws Error_Form_Field
     */
    final public function removeField( $key ){
        if( ! isset($this->fields[$key]) )
            throw new Error_Form_Field("Field {$key} is not defined");

        unset($this->fields[$key]);
        return $this;
    }

    public function getFields( $type = null ){
        if( is_null( $type ) ){
            return $this->fields;
        }

        return array_filter( $this->fields, function( Form_Field $field ) use( $type ){
            return $field instanceof $type;
        } );
    }

    /**
     * @return $this
     */
    public function validate(){
        $this->isValid = true;

        foreach( $this->fields as $field ){
            $field->validate();

            if( ! $field->isValid() )
                $this->isValid = false;
        }

        return $this;
    }

    public function isValid( $validate = false ){
        if( $this->isValid === null ){
            if( $validate ){
                $this->validate();
            } else
                throw new Error_Form("Form is not validated yet");
        }

        return $this->isValid;
    }

    final public function handle( fvRequest $request ){
        $this->setRequest( $request );
        $this->updateFields( $request );

        if( $this->validate() === false )
            $this->isValid = false;

        if( !$this->isValid() )
            return false;

        try{
            $this->process();
            $this->processed = true;
        } catch( Error_Form $e ){
            $this->error = $e;
        }

        return $this;
    }

    abstract protected function process();

    public function updateFields( fvRequest $request ) {
        $values = $request->getRequestParameter($this->getContainerName());
        foreach( $this->fields as $key => $field ){
            if( $field->isDisabled() ){
                continue;
            }

            if( isset( $values[$key] ) ){
                if( is_string($values[$key]) ){
                    $values[$key] = trim($values[$key]);
                }

                $field->setValue( $values[$key] );
            } else {
                $field->setValue( null );
            }
        }
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
	 * @return Strings
     */
    public function getContainerName() {
        if( is_null($this->container) )
            return get_class($this);

        return $this->container;
    }

    public function getName() {
        if( $this->name ){
            return $this->name;
        }

        return get_class($this);
    }

    public function setName ( $name ) {
        $this->name = $name;
        return $this;
    }

    public function getLabel() {
        return $this->getDictionary()->translate( $this->getName() . ".labels.name" );
    }

    public function getSubmitButtonLabel() {
        return $this->getDictionary()->translate( $this->getName() . ".labels.submit" );
    }

    function __get( $name ) {
        return $this->getField($name)->getValue();
    }

    /**
     * @param $request
     * @return $this
     */
    private function setRequest( fvRequest $request ){
        $this->request = $request;
        return $this;
    }

    /**
     * @return fvRequest
     */
    protected function getRequest(){
        return $this->request;
    }

	/**
	 * @param Strings $submitUrl
     * @return $this
     */
    public function setSubmitUrl( $submitUrl ){
        $this->submitUrl = $submitUrl;
        return $this;
    }

	/**
	 * @return Strings
     */
    public function getSubmitUrl(){
        return $this->submitUrl;
    }

    /**
     * @return bool
     */
    public function isProcessed(){
        return $this->processed;
    }

    public function toArray(){
        $result = array();

        foreach( $this->fields as $key => $field ){
            $result[$key] = $field->getValue();
        }

        return $result;
    }

    /**
     * @param \fvDictionary $dictionary
     * @return $this
     */
    public function setDictionary( $dictionary ){
        $this->dictionary = $dictionary;

        return $this;
    }

    /**
     * @return \fvDictionary
     */
    public function getDictionary(){
        if( empty( $this->dictionary ) )
            return fvSite::dictionary();

        return $this->dictionary;
    }

    public function getValues(){
        return array_map( function( Form_Field $field ){
            return $field->getValue();
        }, $this->fields );
    }

    public function setValues( array $map ){
        foreach( $map as $key => $value ){
            $this->getField($key)->setValue($value);
        }
        return $this;
    }

    public function getValidationResult(){
        $values = [];

        foreach( $this->fields as $key => $field ){
            if( ! $field->isValid() ){
                $values[$key] = $field->getValidationMessage();
            }
        }

        return $values;
    }
}
