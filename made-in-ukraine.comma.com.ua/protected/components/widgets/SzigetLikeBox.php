<?php

class SzigetLikeBox extends CWidget {
	public $member_id;
	public $dop_class;

	public function run() {
		$Users = UsersAuth::isLogin();
		$count_votes = MembersLikes::getCount($this->member_id);
		$MembersLikes = null;

		if($Users){
			$MembersLikes = new MembersLikes((int)$this->member_id, (int)$Users->id);
		}

		$this->render('szigetLikeBox', array(
			'Users' => $Users,
			'count_votes' => isset($count_votes['count']) ? $count_votes['count'] : 0,
			'MembersLikes' => $MembersLikes,
		));
	}
}
