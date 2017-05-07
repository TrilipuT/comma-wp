<?php
global $exit_vote_time;
?><div class="vote_area">
	<?php /* if(!$Users):?>
		<button data-id="<?=$this->member_id?>" class="add_vote_submit login add_vote <?=$this->dop_class?>">Проголосувати</button>
	<?php else:
		$status = $MembersLikes->checkStatus();

		if($status):?>
			<?php if(is_array($status)):?>
				<div class="add_vote_already you_choice">Ваш вибiр</div>
			<?php else:?>
				<div class="add_vote_already">Ви вже проголосували</div>
			<?php endif;?>
		<?php elseif($exit_vote_time < time()):?>
			<button data-id="<?=$this->member_id?>" class="add_vote_submit add_vote <?=$this->dop_class?>">Проголосувати</button>
		<?php endif; ?>
	<?php endif; */?>

	<div class="votes_count_block"><?=$count_votes;?></div>
</div>