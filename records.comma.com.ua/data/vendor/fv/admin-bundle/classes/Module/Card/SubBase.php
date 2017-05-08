<?php

namespace AdminBundle\Module\Card;
use Exception;
use Field_Foreign;
use fvQuery;

/**
 * Created by cah4a.
 * Time: 12:27
 * Date: 29.10.13
 */
class SubBase extends Base
{

    private $keyName;
    private $value;

    function __construct( $moduleName, $keyName, $value )
    {
        parent::__construct( $moduleName );

        $foreign = $this->getRootManager()->getEntity()->getForeign( $keyName );

        if( ! $foreign instanceof Field_Foreign ){
            throw new Exception("Field {$keyName} not found");
        }

        $keyName = $foreign->getKey();

        $this->getList()->using( function ( fvQuery $query ) use ( $keyName, $value ){
            $query->andWhere( array(
                $keyName => $value
            ) );
        } );

        $this->keyName = $keyName;
        $this->value = $value;
    }

    public function getCreateUrl()
    {
        return parent::getCreateUrl() . "?keyName={$this->keyName}&value={$this->value}";
    }

    public function getEditUrl()
    {
        return parent::getEditUrl() . "?keyName={$this->keyName}&value={$this->value}";
    }


}