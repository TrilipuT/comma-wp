<?php
/**
 * Created by cah4a.
 * Time: 18:01
 * Date: 14.02.14
 */

class Updater_Row {

    private $errors = array();
    private $changes = array();

    /** @var Product */
    private $product = array();

    function __construct( $values ){
        if( empty( $values["article"] ) ){
            $this->errors[] = "Нет артикула";
            return;
        }

        $product = Updater_Repository::getProduct($values["article"]);

        if( ! $product instanceof Product ){
            $product = new Product();
            $product->article = $values["article"];
        }

        $this->product = $product;
        $this->changes = $this->getChangedFields( $product, $values );
    }

    private function getChangedFields( Product $product, $values ){
        $fieldUpdater = new Updater_Field( $product );

        foreach( $values as $key => $value ){
            $value = trim($value);

            $names = array( "newDueDate", "discountStartDate", "discountEndDate", "isActive" );
            foreach( $names as $realName ){
                if( strtolower($realName) == $key ){
                    $key = $realName;
                }
            }

            try{
                $fieldUpdater->update( $key, $value );
            } catch ( Exception $e ){
                $this->errors[] = $e->getMessage();
            }
        }

        return $fieldUpdater->getChanges();
    }

    function isChanged(){
        return count($this->changes) > 0;
    }

    public function hasErrors(){
        return count($this->errors) > 0;
    }

    public function getErrors(){
        return $this->errors;
    }

    public function getChanges(){
        return $this->changes;
    }

    public function getValue( $key ){
        if( isset( $this->changes[$key] ) ){
            return $this->changes[$key];
        }

        return $this->getOldValue($key);
    }

    public function getOldValue( $key ){
        return $this->product->getField($key)->get();
    }

    public function getProduct(){
        return $this->product;
    }

    public function isKeyChanged( $key ){
        return isset( $this->changes[$key] );
    }

    public function getArticle(){
        if( $this->product )
            return $this->product->article;

        return $this->changes['article'];
    }

    public function isNew(){
        return $this->product->isNew();
    }

    public function getFormName(){
        if( ! $this->isNew() ){
            return "u[{$this->product->getId()}]";
        }

        if( empty($this->seed) )
            $this->seed = uniqid();

        return "n[{$this->seed}]";
    }

} 