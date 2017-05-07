<div class="top_dm clearfix">
	<div class="left_dm">
		<div class="text_md">
			<h3 class="bord"><?=$Members->transfer->name;?></h3>
			<p>
				<?=$Members->transfer->description;?>
			</p>
		</div>

		<?php if($Members->link_fb || $Members->link_vk || $Members->link_lk):?>
			<div class="link_memb">
				<ul class="clearfix">
					<?php if($Members->link_fb):?>
						<li class="fb"><a target="_blank" href="<?=$Members->link_fb?>"></a></li>
					<?php endif;?>
					<?php if($Members->link_vk):?>
						<li class="vk"><a target="_blank" href="<?=$Members->link_vk?>"></a></li>
					<?php endif;?>
					<?php if($Members->link_lk):?>
						<li class="lk"><a target="_blank" href="<?=$Members->link_lk?>"></a></li>
					<?php endif;?>
				</ul>
			</div>
		<?php endif;?>


		<div class="sharing_popup">
			<div class="main" style="display: block">
				<?php $this->widget('application.components.widgets.SzigetLikeBox', array('member_id' => $Members->id, 'dop_class' => 'member_view')); ?>
				<div class="open_soc_block">Поділитися</div>
			</div>

			<div class="message" style="display: none">
				<div class="text"></div>
				<div class="close"></div>
			</div>

			<div class="login_btns" style="display: none">
				<div class="text">Щоб проголосувати, залогіньтеся</div>
				<script>
					jQuery(function($) {
						$(".login-btns a.facebook").eauth({"popup":{"width":585,"height":290},"id":"facebook"});
						$(".login-btns a.vkontakte").eauth({"popup":{"width":585,"height":350},"id":"vkontakte"});
					});
				</script>
				<div id="member_view_socs_block" data-id="<?=$Members->id?>" class="btns login-btns">
					<a onclick="return check_member();" href="/login?service=vkontakte&returnUrl=http://<?=$_SERVER['HTTP_HOST'] . Yii::app()->request->url?>" class="vk vkontakte sziget-soc-login"></a>
					<a onclick="return check_member();" href="/login?service=facebook&returnUrl=http://<?=$_SERVER['HTTP_HOST'] . Yii::app()->request->url?>" class="fb facebook sziget-soc-login"></a>
				</div>
				<div class="close"></div>
			</div>

			<div class="share_btns" style="display: none">
				<?php $this->widget('application.components.widgets.SzigetSocials', array('url' => $Members->getItemUrl(), 'ajax' => $ajax)); ?>
				<div class="close"></div>
			</div>
		</div>
	</div>
	<?php if(!empty($Members->icon_filename) && file_exists($_SERVER['DOCUMENT_ROOT'] . Members::IMAGE_400x500 . $Members->icon_filename)):?>
		<div class="right_dm"><img src="<?=Members::IMAGE_400x500 . $Members->icon_filename?>" alt="<?=$Members->transfer->name;?>" /></div>
	<?php endif;?>
</div>
<p>
	<?=$Members->transfer->text;?>
</p>
<?php if(!empty($Members->transfer->text_soundcloud)):?>
	<div class="center_dm"><?=$Members->transfer->text_soundcloud;?></div>
<?php endif;?>
<?php if(!empty($Members->transfer->text_youtube)):
	$keys = explode(';', $Members->transfer->text_youtube);
	foreach($keys as $youtube_key): ?>
		<div class="bottom_dm">
			<iframe width="100%" height="500" src="https://www.youtube.com/embed/<?=$youtube_key?>?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
		</div>
	<?php endforeach;?>
<?php endif;?>

