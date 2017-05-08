<?php
/**
 * Created by JetBrains PhpStorm.
 * User: iceman
 * Date: 19.09.13
 * Time: 16:58
 * To change this template use File | Settings | File Templates.
 */

class Form_Field_Textarea extends Form_Field {

    private $placeholder = false;

    function __construct ( array $validators = array() ) {
        $this->addValidators( $validators );
    }

    /**
     * @param Strings $placeholder
     *
     * @return $this
     */
    public function showPlaceholder( $placeholder = true ) {
        $this->placeholder = (bool)$placeholder;
        return $this;
    }

    /**
     * @return Strings
     */
    public function getPlaceholder() {
        if( ! $this->placeholder )
            return null;

        $key = $this->getForm()->getName() . ".placeholders." . $this->getKey();
        return $this->getForm()->getDictionary()->translate($key);
    }
}