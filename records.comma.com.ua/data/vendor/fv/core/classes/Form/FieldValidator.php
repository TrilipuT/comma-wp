<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cah4a
 * Date: 21.06.13
 * Time: 12:25
 * To change this template use File | Settings | File Templates.
 */

abstract class Form_FieldValidator {

    abstract public function validate( Form_Field $field );

}