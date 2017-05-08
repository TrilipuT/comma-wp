<?php
/**
 * Created by cah4a.
 * Time: 18:09
 * Date: 11.03.14
 */

class Form_Field_Group extends Form_Field implements Form_FieldContainer  {

    /** @var Form_Field[] */
    private $fields = array();

    private $isValid = null;

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
        return $this;
    }

    public function setForm( fvForm $form, $container, $key ){
        foreach( $this->fields as $fieldKey => $field ){
            $field->setForm( $form, $this, $fieldKey );
        }

        return parent::setForm( $form, $container, $key );
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

    public function setValue( $value ) {
        foreach( $this->fields as $key => $field ){
            if( $field->isDisabled() ){
                continue;
            }

            if( isset( $value[$key] ) ){
                if( !is_array($value[$key]) ){
                    $value[$key] = trim($value[$key]);
                }

                $field->setValue( $value[$key] );
            }
            else
                $field->setValue( null );
        }
    }

    function getContainerName(){
        return $this->getContainer()->getContainerName() . "[{$this->getKey()}]";
    }

    public function getValue(){
        return array_map( function( Form_Field $field ){
            return $field->getValue();
        }, $this->fields );
    }


} 