<div class="comments">

    <?php if(GetRealIp() == "178.216.8.28"):
 endif; ?>

    <?php if(count($commentsItems) > 0):?>
	 
		<div onclick="toCommentBlock($(this)); return false;" class="comments-title addcomment">
            <?=Constants::getItemByKey('wcomments_only')?> <span class="text_count_comments"><?=Yii::t('app', 'wcomments_count_comments', $this->countComments);?></span>
            <a href="#"><?=Constants::getItemByKey('wcomments_leave_yours')?><b></b></a>
        </div>

	<?php else:?> 		
		
		<div class="comments-title">
	        <?=Constants::getItemByKey('wcomments_no_comments_yet')?>
	        <a class="<?=(!$Users ? 'login_popup' : 'to_comments" onclick="toCommentBlock($(this)); return false;')?>"  href="#">
	        	<?=Constants::getItemByKey('wcomments_leave_yours')?>
	        	<b></b>
	        </a>
	    </div> 
	    <div class="clear"></div>
 
	<?php endif;?> 
	

	<div class="comments-content">
		
		<div class="comments-container">
			<?php if(count($commentsItems) > 0):?>  
				<?php $this->controller->renderPartial('/layouts/_comments', array('commentsItems' => $commentsItems, 'lvl' => 0, 'Users' => $Users )); ?> 
			<?php endif;?> 
		</div> 
		

		<?php //if($Users): ?>

			<div class="item item_add">
	            <div class="item-inner">
	                <div class="item-avatar">
	                    <?php if($Users->file_photo != NULL && file_exists($_SERVER['DOCUMENT_ROOT'].Users::PATH_IMAGE.$Users->file_photo)):?>
							<img class="user-logim-img" style="max-width:50px;" src="<?=Users::PATH_IMAGE.$Users->file_photo;?>" alt="<?=$Users->name?>">
						<?php else:?>
							<img class="user-logim-img" style="max-width:50px;" src="/img/ava_clear.png" alt="">
						<?php endif;?>
	                </div>
	                <?php if($Users): ?>
		                <div class="item-top">
		                    <div class="item-name">
		                        <span><?=$Users->name?></span> <a href="#" style="<?=($Users ? 'display:block;' : 'display:none;')?>" class="soc-user-logout" onclick="userLogOut(); return false;"><?=Constants::getItemByKey('wcomments_logout')?></a>
		                    </div>
		                </div>
	                <?php endif;?>
	                <div class="item-form addcommentform">
	                    <textarea maxlength="1000" class="addcommentform__textarea"></textarea>
                    	<button type="<?=$type?>" data-id="<?=$data_id?>" parent="0"  class="addcommentform__submit add-comment"><?=Constants::getItemByKey('wcomments_send')?></button>
	                
	                </div>
	            </div>
	        </div> 
	 
		<?php //else:?>
		<?php /*if(!$Users): ?>
			<div class="item item_login">
                <div class="item-inner">
                    <div  class="user_login">
                        <div class="user_login-title">
                            <?=Constants::getItemByKey('log_in_to_post_notes_attached')?>  
                        </div>
                        <?php $this->widget('application.extensions.eauth.EAuthWidget', array( 'returnUrl' => Yii::app()->request->url)); ?>
                        <div class="user_login-btns">
                            <a href="/login?service=facebook&returnUrl=<?=Yii::app()->request->url?>" class="fb facebook"><b></b><span><?=Constants::getItemByKey('Login_with_Facebook')?></span></a>
                            <span><?=Constants::getItemByKey('or')?></span>
                            <a href="/login?service=vkontakte&returnUrl=<?=Yii::app()->request->url?>" class="vk vkontakte"><b></b><span><?=Constants::getItemByKey('enter_through_vkontakte')?></span></a>
                        </div>
                    </div>
                </div>
            </div>

		<?php endif; */?>

	 </div> 
</div>