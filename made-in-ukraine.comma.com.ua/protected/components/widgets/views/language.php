<?php 

if(count($languageItems) > 0):?>

    <nav>
        <ul class="menu_lang">
			<?php foreach($languageItems as $langCodeName => $langCode): 
					
					if ($_SERVER['REQUEST_URI'] == '/') {
			            $lang_link = '/'.$langCodeName.'/';
					} else {
			            $lang_link = str_replace("/".Yii::app()->language."/", "/".$langCodeName."/", $_SERVER['REQUEST_URI']);  
					}
					
					if ($langCode == $activeLanguage->code) {
						?>
				    	<li class="selected"><a href="<?=$lang_link?>"><?=$langCode?></a></li>
						<?php
					} else {
						?>
				    	<li><a href="<?=$lang_link?>"><?=$langCode?></a></li>
						<?php
					}

				?> 

			<?php endforeach;?>
        </ul>
    </nav>
    
<?php endif; ?>


<!--<nav>
	<ul class="menu_lang">
    	<li class="selected"><a href="ua/">Укр</a></li>
    	<li><a href="ru/">Рус</a></li>
    	<li><a href="en/">Eng</a></li>
    </ul>
</nav>-->