<?php
/**
 * Created by cah4a.
 * Time: 17:15
 * Date: 09.01.14
 */

/**
 * Class Module_List_Table
 * @method Module_Table_Base getBase()
 */
class Module_Table_List extends Module_List
{

    protected $defaultListViewClass = "Module_Table_ItemView";

    public function getListView()
    {
        /** @var Module_Table_ItemView $listView */
        $listView = parent::getListView();
        $listView->setColumns( $this->getBase()->getColumns() );
        $listView->setRemoveEnabled( $this->getBase()->option( "remove", true ) );
        return $listView;
    }


} 