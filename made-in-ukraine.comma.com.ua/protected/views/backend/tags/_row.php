
<?php $rand = md5(microtime().uniqid('tag')); /*
	<!--
	<tr>
		<td><input type="text" name="Tag[<?php echo $rand ?>][ru_name]" value="" style="width: 100%;" /></td>
		<td><input type="text" name="Tag[<?php echo $rand ?>][en_name]" value="" style="width: 100%;" /></td>
		<td><input type="text" name="Tag[<?php echo $rand ?>][ua_name]" value="" style="width: 100%;" /></td>
		<td style="width: 120px; text-align: right;"><a href="#" class="link-remove">Удалить</a></td>
	</tr>
-->
<?php */

$languageList = Language::model()->getLanguageList();
if(count($languageList) > 0): ?> 
    <div class="row">
        <?php foreach ($languageList as $lang_id=>$lang_name): 
  
        	$key = '';
        	if($lang_id == 1){
        		$key = 'ru_name';
        	} else {
        		$key = 'ua_name';
        	}
        ?> 

			<div class="block" style="width: 250px;">
				<input class="nostyle" size="60" maxlength="255" type="text" value="" name="newTagsTransfer[<?php echo $rand ?>][<?=$key;?>]" />
			</div>

		<?php endforeach;?>
		<button type-data="del-tag" data-id="new" class="del-tag btn btn-danger btn-mini" href="#">Удалить</button>
	</div>
<?php endif; 
/*
<!--
		<div class="block">
			<input class="tag-autocomplete" size="60" maxlength="255" type="text" value="" name="newTagsTransfer[2][]">
		</div>
-->
*/		
 	