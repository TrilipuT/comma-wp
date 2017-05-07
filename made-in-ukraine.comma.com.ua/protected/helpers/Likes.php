<?php

/**
 * префиск таблиц пока сделал вшитым. это надо будет как то поправить если будет необходимость
 * http://php.net/manual/ru/pdo.constants.php - Предопределенные константы
 */
class Likes {
	private $_id = 0;
	private $_userId = 0;
	private $_tmpUserId = 0;
	private $_ip; // long

	private $_active = 1;

	public function __construct($id, $userId = 0) {

		$this->_id = $id;
		//--------------------------------------
		$this->_ip = ip2long(Yii::app()->getRequest()->getUserHostAddress());
		//--------------------------------------
		if ($userId > 0) {
			$this->_userId = $userId;
		}
		//если не залогинен, тогда мы оприделяем его временный id (пока еще в тесте)
		if ($this->_userId == 0) {
			$md5 = md5('tmpUserId');
			$tmpUserId = Yii::app()->request->cookies[$md5]->value;
			if (!$tmpUserId) {
				$tmpUserId = ceil((time() + $this->_ip) / 14);
				if ($tmpUserId <= 0) {
					$tmpUserId = 1;
				}
				$cookie = new CHttpCookie($md5, $tmpUserId);
				$cookie->domain = Yii::app()->params['main_domain'];
				$cookie->expire = time() + 60 * 60 * 24 * 180;
				Yii::app()->request->cookies[$md5] = $cookie;
			}

			$this->_tmpUserId = $tmpUserId;
		}
	}

	/**
	 * @idComment - проверенный id блога
	 * @return int
	 */
	public static function getCount($id) {

		$sql = "SELECT COUNT(*) as count
					FROM comma_likes_count AS t1
					WHERE t1.comment_id = :comment_id AND t1.active = 1";

		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(":comment_id", $id, PDO::PARAM_STR);
		$row = $command->queryRow();
		//----------------------------------------------------------------------------
		/*
		Yii::app()->db->createCommand('UPDATE '.Blogs::model()->tableName().' as t1
										SET t1.`likes_num` = '.$row['count'].'
										WHERE t1.id = '.$id)->execute();
		*/
		//-----------------------------------------------------------------------------
		$result = array('count' => $row['count']);

		return $result;
	}

	/**
	 * @return false/array
	 */
	public function checkStatus() {

		if ($this->_userId > 0) {

			$sql = "SELECT t1.*
						FROM comma_likes_count AS t1
						WHERE comment_id = :comment_id AND user_id = :user_id";

			$command = Yii::app()->db->createCommand($sql);
			$command->bindParam(":user_id", $this->_userId, PDO::PARAM_INT);

		} else if ($this->_tmpUserId > 0) {

			$sql = "SELECT t1.*
					FROM comma_likes_count AS t1
					WHERE comment_id = :comment_id AND tmp_user_id = :tmp_user_id";

			$command = Yii::app()->db->createCommand($sql);
			$command->bindParam(":tmp_user_id", $this->_tmpUserId, PDO::PARAM_INT);
		}
		//-------------------------------------------------------------------------
		if ($command) {
			$command->bindParam(":comment_id", $this->_id, PDO::PARAM_STR);
			$row = $command->queryRow();

			if ($row) {
				return array('id' => $row['id'], 'active' => $row['active']);
			}
		}

		return false;
	}

	/**
	 * @type - тип (like/dislike)
	 * @return boolean
	 */
	public function _set($type = "top") {

		$this->_active = 0;
		if ($type == "top") { //нажата не активная кнопка
			$this->_active = 1;
		}

		$status = $this->checkStatus();
		if ($status) {
			$_status = $this->update($status['id']);
		} else { // это значит ничего не нашло и можно добавить запись
			$_status = $this->save();
		}

		return $_status;
	}

	private function save() {

		$sql = "INSERT INTO comma_likes_count
						(comment_id, user_id, tmp_user_id, active, ip, datetime)
					VALUES
						(:comment_id, :user_id, :tmp_user_id, :active, :ip, :datetime)";

		$date = date('Y-m-d H:i:s');
		$command = Yii::app()->db->createCommand($sql);

		$command->bindParam(":comment_id", $this->_id, PDO::PARAM_INT);
		$command->bindParam(":user_id", $this->_userId, PDO::PARAM_INT);
		$command->bindParam(":tmp_user_id", $this->_tmpUserId, PDO::PARAM_INT);
		$command->bindParam(":active", $this->_active, PDO::PARAM_BOOL);
		$command->bindParam(":ip", $this->_ip, PDO::PARAM_INT);
		$command->bindParam(":datetime", $date, PDO::PARAM_STR);

		return $command->execute();
	}

	private function update($id) {

		$sql = "UPDATE comma_likes_count
					SET active  = :active 
					WHERE id = :id";

		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(":id", $id, PDO::PARAM_INT);
		$command->bindParam(":active", $this->_active, PDO::PARAM_BOOL);

		return $command->execute();
	}
	//https://graph.facebook.com/fql?q=SELECT url, normalized_url, share_count, like_count, comment_count, total_count,commentsbox_count, comments_fbid, click_count FROM link_stat WHERE url='http://www.google.com'
	//https://api.facebook.com/method/links.getStats?urls=google.com&format=json

	//http://www.nulled.cc/threads/242507/
	//https://developers.facebook.com/docs/graph-api/reference/v2.2/page?locale=ru_RU
	//https://developers.facebook.com/tools/explorer/?method=GET&path=me%3Ffields%3Did%2Cname&version=v2.2
	
	public static function vkShares($url) {
		//количество расшариваний на vk
		$vk_request = @file_get_contents('http://vk.com/share.php?act=count&index=1&url=' . $url);
		$temp = array();
		preg_match('/^VK.Share.count\(1, (\d+)\);$/i', $vk_request, $temp);

		//в $temp[1] количество расшариваний, то есть сколько раз нажали "рассказать друзьям"
		return $temp[1];
	}

	public static function fb_likes($url) {
		$json = json_decode(@file_get_contents("http://graph.facebook.com/?id=" . urlencode($url)));

		return $json !== false ? intval($json->shares) : false;
	}
} 