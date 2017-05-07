<!--
<div class="likes">
    <div class="likes-title">
        <?=Constants::getItemByKey('like_title')?>
    </div>
 
    <a href="?hash=1" class="like fb l-fb">
		<b class="l-ico"></b>
		<span style="margin-left:-5px;cursor: default;" class="l-count">0</span>
	</a>

    <a href="?hash=1" class="like tw l-tw">
		<b class="l-ico"></b>
		<span style="margin-left:-5px;cursor: default;" class="l-count">0</span>
	</a>

	<a href="?hash=1" class="like vk l-vk">
		<b class="l-ico"></b>
		<span style="margin-left:-5px;cursor: default;" class="l-count">0</span>
	</a>   

</div>
-->
<div class="likes">
	<div class="likes-title">
	    <?=Constants::getItemByKey('like_title')?>
	</div>

	<div class=" social-likes">  
		
		<div class="fb l-fb facebook" title="Поделиться ссылкой на Фейсбуке">Facebook</div>
		<div class="tw l-tw twitter" title="Поделиться ссылкой в Твиттере">Twitter</div>
		<div class="vk l-vk vkontakte" title="Поделиться ссылкой во Вконтакте">Вконтакте</div>
	</div>
</div>
 