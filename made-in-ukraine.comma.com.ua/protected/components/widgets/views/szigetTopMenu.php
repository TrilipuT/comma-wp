<?php if($sectionItems):?>
	<div class="header_menu">
		<nav>
			<ul>
        <?php foreach ($sectionItems as $key => $Section):?>
				<li class="<?=($this->getController()->Section->code_name == $Section->code_name ? 'active' : '')?>">
					<a href="<?=$Section->getItemUrl()?>">
						<?=$Section->transfer->name?>
					</a>
				</li>
        <?php endforeach;?>
			</ul>
		</nav>
	</div>
<?php endif;?>