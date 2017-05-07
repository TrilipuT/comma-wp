<?php
    $menuItems = array();
    foreach($sectionItems as $Section) {
		if ($this->getController()->Section->code_name == $Section->code_name) {
	        $menuItems[] = '<li class="selected"><a href="'.$Section->getUrl().'">'.$Section->transfer->menu_name.'</a><img src="/img/menu_main_selected_back.png" alt="" /></li>';
		} else {
	        $menuItems[] = '<li><a href="'.$Section->getUrl().'">'.$Section->transfer->menu_name.'</a></li>';
		}
    }
    $menuList = implode('', $menuItems);
?> 
<nav>
    <ul class="menu_main">
    <?=$menuList?>
    </ul>
</nav>
