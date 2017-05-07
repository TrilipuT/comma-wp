<?php

class VKLIKES {
	private $url = 'https://api.vk.com/method/'; // URL к методам API

	private $owner_id; //ID автора поста
	private $post_id; //ID поста

	private $count = 1000; //По сколько "репостов" и "лайков" доставать
	private $users = array(); //Массив с пользователями
	private $countReposts; //Количество репостов у текущего пользователя
	private $findPost; //ID найденного репоста в пользовательских новостях
	private $find; //Флаг найден/не найден репост у пользователя

	public function __construct($owner_id = '', $post_id = '') {
		$this->owner_id = $owner_id;
		$this->post_id = $post_id;
	}

//Сообщение о действиях
	private function printProgress($text, $start = true, $error = false) {
		if ($error) {
			$color = 'red';
		} else if ($start) {
			$color = '#444';
		} else {
			$color = 'green';
		}

		echo '<li style="color: ' . $color . ';">' . $text . '</li>';
		ob_flush();
		flush();
	}

	/*Считываем всех пользователей, кто поделился нашим постом
	* $onwer_id - ID автора поста
	* $post_id - ID-поста
	* $fitler - "likes" или "copies"
	* $offset - смещение по пользователям. Можно достать максимум 1000 пользователей
	* $onlyCount - вернуть только количество репостов
	* $start - используется для рекурсии
	*/
	private function getUsers($owner_id, $post_id, $filter, $offset = 0, $onlyCount = false, $start = true) {
//формируем URL со всеми параметрами
		$url = $this->url . 'likes.getList?type=post&friends_only=0&offset=' . $offset . '&count=' . $this->count . '&owner_id=' . $owner_id . '&item_id=' . $post_id . '&filter=' . $filter;

//получаем результат запроса в JSON-фомате
		$json = file_get_contents($url);
//преобразуем JSON в ассоциативный массив
		$data = json_decode($json, true);

//если ответ, не содержит нужных данных
		if (!isset($data['response'])) {
			return false;
		}

		$response = $data['response'];
		$count = $response['count']; //Получаем количество пользователей

//Понадобится, когда нужно будем определить ТОЛЬКО количество репостов у пользователя
		if ($onlyCount) {
			$this->countReposts = $count;

			return true;
		}

//Далее рекурсивно будет получать пользователей до тех пор, пока не считаем все. При этом сдвигаем offset.
		$users = $response['users'];

		if (count($users) == 0) {
			return false;
		}

		if ($start) {
			$this->users = $users;
		} else {
			$this->users = array_merge($this->users, $users);
		}

		$offset += $this->count;

		$this->getUsers($owner_id, $post_id, $filter, $offset, $onlyCount, false);
	}

//Для удобства я изменил ключи в массиве. Ключами являются - ID пользователя сайта vk.com
	private function remakeUsersArray($usersWithInfo) {
		$new = array();
		foreach ($usersWithInfo as $value) {
			$new[$value['uid']] = $value;
		}

		return $new;
	}

	/* Получить информацию о пользователях
	* $vkIDs - массив с ID пользователей
	*/
	function getUsersInfo($vkIDs) {
//$count = 1000;

//Для получения информации о пользователе, используются положительные ID (ID со знаком минус имеют группы, сообщества)
		foreach ($vkIDs as $key => $val) {
			if ((int)$val < 0) {
				unset($vkIDs[$key]);
			}
		}

		$uids = implode(',', $vkIDs);
		$fields = 'uid,first_name,last_name,nickname,screen_name,sex,city,country,timezone,photo,photo_medium,photo_big,has_mobile,rate,online,counters';
		$url = $this->url . 'users.get?&uids=' . $uids . '&fields=' . $fields . '&name_case=nom';

		$json = file_get_contents($url);
		$data = json_decode($json, true);
		if (isset($data['response'])) {
			$response = $data['response'];

			return $response;
		}

		return 0;
	}

//Получам посты пользователя
	private function getUsersPosts($owner_id, $offset = 0) {
		$maxNews = 600; //Максимальное колчиство новостей для поиска
		$count = 100; //100 - это максимальное количество новостей, которые можно получить за один запрос

//Если обыскали $maxNews новостей и не нашли
		if ($offset > $maxNews - $count) {
			$this->printProgress('<b>Репост не был найден среди ' . $maxNews . ' новостей...</b>', false, true);
			$this->find = false;

			return false;
		}
//Формируем URL
		$url = $this->url . 'wall.get?';
		$url .= 'owner_id=' . $owner_id . '&';
		$url .= 'offset=' . $offset . '&';
		$url .= 'count=' . $count . '&';
		$url .= 'filter=owner';

		$json = file_get_contents($url); //Получаем JSON-ответ
		$data = json_decode($json, true);

//Если вдруг страница пользователя "заморожена" или удалена
		if (!isset($data['response'])) {
			$this->printProgress('<b>Ошибка получения нововстей</b>', false, true);
			$this->find = false;

			return false;
		}

		$response = $data['response'];
		$this->printProgress('Поиск нашего репоста среди ' . ($count + $offset) . ' новостей..');

//Обрабатываем $count новостей
		foreach ($response as $news) {
			if (!is_array($news)) {
				continue;
			}

			/* copy_owner_id - ID моей страницы или группы
			* copy_owner_id - ID моего поста
			*/
			if (isset($news['copy_owner_id'], $news['copy_post_id']) and $news['copy_owner_id'] == $this->owner_id and $news['copy_post_id'] == $this->post_id) {
				$this->users[$news['from_id']]['repost_id'] = $news['id'];
				$this->printProgress('<b>Репост успешно найден найден #' . $news['id'] . '</b>', false);
				$this->findPost = $news['id'];
				$this->find = true;

				return true;
			}
		}

		$offset += $count; //Увеличиваем смещение
		$this->getUsersPosts($owner_id, $offset); //Рекурсия
	}

//Поиск репоста
	function findReposts() {
		echo '<ul>';
		$this->printProgress('Получаю список ID пользователей, сделавших репост...');
		$this->getUsers($this->owner_id, $this->post_id, 'copies');
		$this->printProgress('Список ID успешно получен', false);
		$copies = $this->users;

		$this->printProgress('Получаю список ID пользователей, сделавших лайк...');
		$this->getUsers($this->owner_id, $this->post_id, 'likes');
		$this->printProgress('Список ID успешно получен', false);

		foreach ($this->users as $id) {
			if (in_array($id, $copies)) {
				continue;
			}
			$copies[] = $id;
		}

		$this->users = $copies;
		$this->printProgress('<b>Уникальных ID пользователей для получения их информации: ' . count($this->users) . '</b>');

		$this->printProgress('Получаю информацию о пользователях по их ID...');
		$usersWithInfo = $this->getUsersInfo($this->users);

		$this->printProgress('Информация была успешно получена', false);
		$this->printProgress('<b>Уникальных ID пользователей с информации: ' . count($usersWithInfo) . '</b>');
		$this->printProgress('Подготавливаю массив с информацией о пользователях...');
		$this->users = $this->remakeUsersArray($usersWithInfo);
		$this->printProgress('Массив успешно сформирован', false);

		$this->printProgress('Начинаем искать репосты у пользователей...');
		$k = 1;

		foreach ($this->users as $id => $data) {
			$this->printProgress('<i>' . $k . ') Обрабатывается пользователь: <a href="http://vk.com/id' . $id . '">id' . $id . '</a> - ' . $data['last_name'] . ' ' . $data['first_name'] . '</i>');
			$this->getUsersPosts($id);

			if ($this->find) {
				$this->printProgress('Определяем количество репостов #' . $this->findPost . ' у пользователя...');
				$this->getUsers($id, $this->findPost, 'copies', 0, true);

				$status = '<span';
				if ($this->countReposts > 0) {
					$status .= ' style="font-size: 20px;"';
				}
				$status .= '>Количество репостов #' . $this->findPost . ': <b>' . $this->countReposts . '</b></span>';

				$this->printProgress($status, false);
				$this->users[$id]['count_reposts'] = $this->countReposts;

				//тут можно добавлять $this->user[$id]  в сессию
			}

			$k++;
		}

		$this->printProgress('Поиск репостов завершен', false);
	}
}