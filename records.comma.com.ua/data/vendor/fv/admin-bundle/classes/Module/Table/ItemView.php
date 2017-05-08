<?php

namespace AdminBundle\Module\Table;

use AdminBundle\Module\ItemView as ModuleItemView;

class ItemView extends ModuleItemView
{
    protected $columns;
    private $remove;

    protected function assignVars()
    {
        $this->view()->assignParams( array(
            "columns" => $this->columns,
            "remove" => $this->remove,
        ) );
    }

    public function setColumns( $columns )
    {
        $this->columns = $columns;
    }

    public function setRemoveEnabled( $remove )
    {
        $this->remove = $remove;
    }

}