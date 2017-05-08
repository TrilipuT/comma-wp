<?php

namespace AdminBundle\Module\Table;

use AdminBundle\Module\Base as ModuleBase;

/**
 * Created by cah4a.
 * Time: 17:17
 * Date: 09.01.14
 */
class Module_Table_Base extends ModuleBase
{

    protected $defaultListClass = "Module_Table_List";

    public function getColumns()
    {
        return $this->getRootManager()->getRootObj()->getFieldList();
    }

}