<?php

class MembersLikes {
	private $_member_id;
	private $_userId;
	private $_ip; // long

	public function __construct($member_id, $userId) {
		$this->_member_id = $member_id;
		$this->_ip = ip2long(Yii::app()->getRequest()->getUserHostAddress());
		$this->_userId = $userId;
	}

	public static function getCount($id_member) {
		$sql = "SELECT COUNT(*) as count
				FROM comma_member_likes_count AS t1
				WHERE t1.member_id = :member_id AND t1.active = 1";

		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(":member_id", $id_member, PDO::PARAM_STR);
		$row = $command->queryRow();
		//----------------------------------------------------------------------------
		$result = array('count' => $row['count']);

		return $result;
	}

	/**
	 * @return false/array
	 */
	public function checkStatus() {
		if ($this->_userId <= 0) {
			return false;
		}

		$sql = "SELECT t1.*
				FROM comma_member_likes_count AS t1
				WHERE user_id = :user_id"; //member_id = :member_id AND

		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(":user_id", $this->_userId, PDO::PARAM_INT);
		//$command->bindParam(":member_id", $this->_member_id, PDO::PARAM_STR);
		$row = $command->queryRow();

		if ($row) {
			if($row['member_id'] == $this->_member_id){
				return array('id' => $row['id'], 'active' => $row['active']);
			}

			return true;
		}

		return false;
	}

	public function _set() {
		$this->_active = 1;
		$status = $this->checkStatus();

		if ($status) {
			$_status = $this->update($status['id']);

			if($_status){
				$_status = 2;
			} else {
				$_status = -1;
			}
		} else { // это значит ничего не нашло и можно добавить запись
			$_status = $this->save();

			if($_status){
				$_status = 1;
			} else {
				$_status = -1;
			}
		}

		return $_status;
	}

	private function save() {
		$sql = "INSERT INTO comma_member_likes_count
					(member_id, user_id, active, ip, datetime)
				VALUES
					(:member_id, :user_id, :active, :ip, :datetime)";

		$date = date('Y-m-d H:i:s');
		$command = Yii::app()->db->createCommand($sql);

		$command->bindParam(":member_id", $this->_member_id, PDO::PARAM_INT);
		$command->bindParam(":user_id", $this->_userId, PDO::PARAM_INT);
		$command->bindParam(":active", $this->_active, PDO::PARAM_BOOL);
		$command->bindParam(":ip", $this->_ip, PDO::PARAM_INT);
		$command->bindParam(":datetime", $date, PDO::PARAM_STR);

		return $command->execute();
	}

	private function update($id) {

		$sql = "UPDATE comma_member_likes_count
				SET active  = :active
				WHERE id = :id";

		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(":id", $id, PDO::PARAM_INT);
		$command->bindParam(":active", $this->_active, PDO::PARAM_BOOL);

		return $command->execute();
	}
}
