<?php
$count_items = count($membersItems);
if ($count_items > 0):
    foreach ($membersItems as $Members):
		if(empty($Members->image_filename) && !file_exists($_SERVER['DOCUMENT_ROOT'] . Members::IMAGE_490x300 . $Members->image_filename) ){
			continue;
		}
		?>
		<?php if($key%2 == 0):?>
			<div class="members_row clearfix">
		<?php endif;?>

			<div class="members_item">
				<div class="memb_click" data-href="<?=$Members->getItemUrl()?>">
					<span class="memb_img">
						<img src="<?=Members::IMAGE_490x300 . $Members->image_filename?>" alt="<?=$Members->transfer->name;?>" />
					</span>
					<span class="memb_hide">
						<span class="memb_h_content">
							<span class="mh_title"><?=$Members->transfer->name;?></span>
							<div><?=$Members->transfer->description;?></div>
							<span style="display: none" class="sharing_memb">
								<?php //$this->widget('application.components.widgets.SzigetSocials', array('url' => $Members->getItemUrl(), 'ajax' => true)); ?>
							</span>
							<?php $this->widget('application.components.widgets.SzigetLikeBox', array('member_id' => $Members->id)); ?>
						</span>
					</span>
				</div>
			</div>

		<?php if(($key%2) == 1 || ($key+1) == $count_items):?>
			</div>
		<?php endif;
		$key++;
	endforeach;?>
<?php endif;?>  