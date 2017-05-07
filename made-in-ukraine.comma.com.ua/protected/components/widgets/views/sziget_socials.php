<ul>
	<li>
		<div class="vk_social">
			<?php /*
 https://developers.facebook.com/docs/plugins/send-button?locale=ru_RU

 https://vk.com/pages?oid=-17680044&p=Sharing_External_Pages
 https://vk.com/dev/share_details

			*/
			if(!$this->ajax):?>
				<script type="text/javascript">
					<!--
					document.write(VK.Share.button(
						'<?=$this->url?>',
						{type: 'button', text: 'Подiлитися'}
					));
					-->
				</script>
			<?php else:?>
				<div data-href="<?=$this->url?>" data-text="Подiлитися" class="vk_share_button"></div>
			<?php endif;?>
		</div>
	</li>
	<li>
		<div class="fb_social">
			<div
				class="fb-share-button"
				data-layout="button_count"
				data-href="<?=$this->url?>"
				data-width="150" >
			</div>
		</div>
	</li>
</ul>
