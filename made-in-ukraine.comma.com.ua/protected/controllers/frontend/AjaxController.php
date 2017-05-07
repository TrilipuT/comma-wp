<?php
class AjaxController extends FrontEndController {
	public function actionGetMoreItems($q = '') {

		$result = array('success' => 0);

		Yii::app()->language = Yii::app()->getRequest()->getPost('lang');
		$type = Yii::app()->getRequest()->getPost('type');
		$page = (int)Yii::app()->getRequest()->getPost('page');
		$catId = Yii::app()->getRequest()->getPost('cat');
		$notId = (int)Yii::app()->getRequest()->getPost('notId');
		$tag = (int)Yii::app()->getRequest()->getPost('tag');

		$date = Yii::app()->getRequest()->getPost('date');

		switch ($type) {
			case 'pres_relіzi':
				$_result = Pressrelease::getItems('', $catId, $page);

				if ($_result['itemsCount'] > 0) {

					foreach ($_result['items'] as &$v) {
						if ($v->type == 'doc' || $v->type == 'docx') {
							$v->type = array($v->type, 'word');
						} else if ($v->type == 'ppt' || $v->type == 'pptx') {
							$v->type = array($v->type, 'ppt');
						} else if ($v->type == 'pdf') {
							$v->type = array($v->type, 'pdf');
						}
					}
					$html = $this->renderPartial('/c_press_relizu/_items', array('items' => $_result['items']), true);

					$remains = $_result['remains'];

					if ($remains > 0) {
						//$remains = Yii::t('app','download_more')." ".Yii::t('test', '{n} тренинг|{n} тренинга|{n} тренингов|{n} тренинг', $remains);
					}

					$result = array('success' => 1, 'html' => $html, 'remains' => $remains);

				}
				break;

			case 'news':

				$_result = News::getItems($catId, $date, $page);

				if ($_result['itemsCount'] > 0) {

					$html = $this->renderPartial('/c_news/_items', array('news' => $_result['items']), true);

					$remains = $_result['remains'];

					if ($remains > 0) {
						//$remains = Yii::t('app','download_more')." ".Yii::t('test', '{n} тренинг|{n} тренинга|{n} тренингов|{n} тренинг', $remains);
					}

					$result = array('success' => 1, 'html' => $html, 'remains' => $remains);
				}
				break;

			case 'videos':

				$Program = Program::model()->findByPk($catId);
				if ($Program) {
					$onPage = 6;
				} else {
					$onPage = 12;
				}

				$_result = Video::getItems($date, $catId, $notId, $page, $onPage);

				// print_r($_result);

				if ($_result['itemsCount'] > 0) {

					$html = $this->renderPartial('/c_videos/_items', array('items' => $_result['items'], 'Program' => $Program), true);

					$remains = $_result['remains'];

					if ($remains > 0) {
						//$remains = Yii::t('app','download_more')." ".Yii::t('test', '{n} тренинг|{n} тренинга|{n} тренингов|{n} тренинг', $remains);
					}

					$result = array('success' => 1, 'html' => $html, 'remains' => $remains);
				}

				break;
			case 'search':
				$category = $catId;
				$onPage = 20;
				$sResult = array();
				$resultLeft = array();
				if ($q != '') {
					if ($category == '' || $category == 'news') {
						$news = News::model()->published()->searchByKeywords($q)->orderByDateDesc()->findAll();
					} else {
						$news = array();
					}
					if ($category == '' || $category == 'videos') {
						$videos = Video::model()->published()->searchByKeywords($q)->orderByDateDesc()->findAll();
					} else {
						$videos = array();
					}
					if ($category == '' || $category == 'pressreleases') {
						$pressreleases = Pressrelease::model()->published()->searchByKeywords($q)->orderByDateDesc()->findAll();
					} else {
						$pressreleases = array();
					}
				} else {
					$news = array();
					$videos = array();
					$pressreleases = array();
				}

				foreach ($news as $News) {
					$sResult[] = $News;
				}
				foreach ($videos as $Video) {
					$sResult[] = $Video;
				}
				foreach ($pressreleases as $PR) {
					$sResult[] = $PR;
				}

				$resultOffset = ($page - 1) * $onPage;
				$pageResult = array_slice($sResult, $resultOffset, $onPage);
				$resultLeft = count($sResult) - $page * $onPage;

				$html = $this->renderPartial('/c_search/_items', array('result' => $pageResult, 'q' => $q), true);
				$result = array('success' => 1, 'html' => $html, 'remains' => $resultLeft);
				break;

			case 'tag':
				$category = $catId;
				$onPage = 20;
				$sResult = array();
				$resultLeft = array();
				if ($tag != '') {
					if ($category == '' || $category == 'news') {
						$news = News::model()->published()->searchByTag($tag)->orderByDateDesc()->findAll();
					} else {
						$news = array();
					}
					if ($category == '' || $category == 'videos') {
						$videos = Video::model()->published()->searchByTag($tag)->orderByDateDesc()->findAll();
					} else {
						$videos = array();
					}
					if ($category == '' || $category == 'pressreleases') {
						$pressreleases = Pressrelease::model()->published()->searchByTag($tag)->orderByDateDesc()->findAll();
					} else {
						$pressreleases = array();
					}
				} else {
					$news = array();
					$videos = array();
					$pressreleases = array();
				}

				foreach ($news as $News) {
					$sResult[] = $News;
				}
				foreach ($videos as $Video) {
					$sResult[] = $Video;
				}
				foreach ($pressreleases as $PR) {
					$sResult[] = $PR;
				}

				$resultOffset = ($page - 1) * $onPage;
				$pageResult = array_slice($sResult, $resultOffset, $onPage);
				$resultLeft = count($sResult) - $page * $onPage;

				$html = $this->renderPartial('/c_tag/_items', array('result' => $pageResult), true);
				$result = array('success' => 1, 'html' => $html, 'remains' => $resultLeft);
				break;

			case 'events':

				$_result = Event::getItems($date, $page);

				if ($_result['itemsCount'] > 0) {

					$html = $this->renderPartial('/c_events/_items', array('items' => $_result['items']), true);

					$remains = $_result['remains'];

					if ($remains > 0) {
						//$remains = Yii::t('app','download_more')." ".Yii::t('test', '{n} тренинг|{n} тренинга|{n} тренингов|{n} тренинг', $remains);
					}

					$result = array('success' => 1, 'html' => $html, 'remains' => $remains);
				}
				break;
		}

		header('Content-type: application/json');
		echo json_encode($result);
	}

	public function actionSiteLogin() {

		$result = array('success' => 0);
		Yii::app()->language = Yii::app()->getRequest()->getPost('lang');
		$email = Yii::app()->getRequest()->getPost('email');
		$password = Yii::app()->getRequest()->getPost('password');

		if ($email != null && $password != null) {

			$UsersAuth = new UsersAuth('site', $email, $password);

			if ($UsersAuth->getUserId() > 0) {
				$result = array('success' => 1, 'message' => Yii::t('app', 'login_success'));
			} else {
				$result = array('success' => 0, 'message' => Yii::t('app', 'login_failed'));
			}
		}

		header('Content-type: application/json');
		echo json_encode($result);
	}

	public function actionUserRegistration() {

		$result = array('success' => 0);
		Yii::app()->language = Yii::app()->getRequest()->getPost('lang');
		$name = trim(Yii::app()->getRequest()->getPost('name'));
		$date = trim(Yii::app()->getRequest()->getPost('date'));
		$town = trim(Yii::app()->getRequest()->getPost('town'));
		$sfere = trim(Yii::app()->getRequest()->getPost('sfere'));
		$email = trim(Yii::app()->getRequest()->getPost('email'));
		$password = trim(Yii::app()->getRequest()->getPost('password'));

		if ($email != null && $password != null && $date != null && $town != null && $sfere != null) {

			$Users = Users::model()->find('mail = :mail', array(':mail' => $email));
			if ($Users) {
				$result = array('success' => 0, 'message' => Yii::t('app', 'user_mail_isset'));
			} else {
				$Users = new Users();
				$Users->password = md5($password);
				$Users->name = $name;
				$Users->mail = $email;
				$Users->provider = 'site';
				$Users->active = 1;
				$Users->birthday = $date;
				$Users->sfere = $sfere;
				$Users->town = $town;

				$Users->save();

				$cookie = new CHttpCookie('user_login', $Users->id);
				$cookie->expire = time() + 60 * 60 * 24;
				Yii::app()->request->cookies['user_login'] = $cookie;

				$result = array('success' => 1, 'message' => Yii::t('app', 'registration_success'));
			}
		}

		header('Content-type: application/json');
		echo json_encode($result);
	}

	public function actionuserLogOut() {

		$result = array('success' => 0);

		$Users = UsersAuth::isLogin();
		if (!$Users) {

			$result = array('success' => 0, 'message' => Yii::t('app', 'user_not_login'));

		} else {
			UsersAuth::logOut();
			$result = array('success' => 1);
		}

		header('Content-type: application/json');
		echo json_encode($result);
	}

	public function actionAddComment() {

		$result = array('success' => 0);
		Yii::app()->language = Yii::app()->getRequest()->getPost('lang');
		$parent = (int)Yii::app()->getRequest()->getPost('parent');
		$data_id = (int)Yii::app()->getRequest()->getPost('data_id');
		$type = trim(Yii::app()->getRequest()->getPost('type'));
		$text = trim(Yii::app()->getRequest()->getPost('text'));

		if (mb_strlen(($text), 'utf-8') > 1000) {
			$text = mb_substr($text, 0, 1000, 'utf-8') . '...';
		}

		//$text = str_replace("\n", "<br/>", $text);

		$Users = UsersAuth::isLogin();
		if (!$Users) {

			$cookie = new CHttpCookie('user_not_login_comment', 1);
			$cookie->expire = time() + 60 * 10;
			Yii::app()->request->cookies['user_not_login_comment'] = $cookie;

			$session = new CHttpSession;
			$session->open();

			$_array['user_comment_text'] = $text;
			$_array['user_comment_parent'] = $parent;
			$_array['user_comment_type'] = $type;
			$_array['user_comment_data_id'] = $data_id;

			$session['user_comment'] = $_array;

			$result = array('success' => 0, 'message' => Yii::t('app', 'user_not_login'));

		} else {

			if ($type != null && $text != null) {

				$GetComments = new GetComments($type);
				$GetComments->withType($data_id);
				$GetComments->setUser($Users->id);

				if ($GetComments->checkLastAdd($text)) {

					$Comment = new Comment();
					switch ($type) {
						case 'news':
							$Comment->news_id = $data_id;
							break;
						case 'article':
							$Comment->article_id = $data_id;
							break;
						case 'video':
							$Comment->video_id = $data_id;
							break;
						case 'gallery':
							$Comment->gallery_id = $data_id;
							break;
						default:
							return false;
					}

					$Comment->user_id = $Users->id;
					$Comment->comment_id = $parent;
					$Comment->datetime = date('Y-m-d H:i:s');
					$Comment->content = $text;//CHtml::encode($text);
					$Comment->active = 1;

					if ($Comment->save()) {
						Comment::recountAllComments(0, $data_id, $type);
						//-------------------------------------------
						$GetComments = new GetComments($type);
						$GetComments->withType($data_id);
						$GetComments->setActive(1);
						$commentsItems = $GetComments->getCommentsRecursive();
						//-----------------------------------------------------
						$html = $this->renderPartial('/layouts/_comments', array('commentsItems' => $commentsItems, 'lvl' => 0, 'Users' => $Users), true);
						//-----------------------------------------------------
						switch ($type) {
							case 'news':
								$model = News::model()->published()->findByPk($data_id);
								break;
							case 'article':
								$model = Article::model()->published()->findByPk($data_id);
								break;
							case 'video':
								$model = Videos::model()->published()->findByPk($data_id);
								break;
							case 'gallery':
								$model = Gallery::model()->published()->findByPk($data_id);
								break;
							default:
								return false;
						}

						$count_comments = Yii::t('app', 'wcomments_count_comments', $model->comments_num);
					}

					$result = array('success' => 1, 'count_comments' => $count_comments, 'message' => '', 'html' => $html);
				} else {
					$result = array('success' => 0, 'message' => Yii::t('app', 'user_comment_time_error'));
				}

			} else if ($text == null) {
				$result = array('success' => 0, 'message' => Yii::t('app', 'user_comment_text_empty'));
			}
		}

		header('Content-type: application/json');
		echo json_encode($result);
	}

	public function actionChangeRate() {

		$result = array('success' => 0);
		Yii::app()->language = Yii::app()->getRequest()->getPost('lang');
		$data_id = (int)Yii::app()->getRequest()->getPost('data_id');
		$type = trim(Yii::app()->getRequest()->getPost('type'));

		$Users = UsersAuth::isLogin();
		if (!$Users) {
			$result = array('success' => 0, 'message' => 'user_not_login');
		} else {

			$Comment = Comment::model()->findByPk($data_id);

			if (!$Comment) {
				throw new CHttpException(404, 'Страница "' . $data_id . '" не найдена.');
			}

			$Likes = new Likes((int)$Comment->id, (int)$Users->id);
			if ($Likes->_set($type)) {
				//$info   = Likes::getCount($Comment->id);
				//$result = array('success' => 1, 'data' => $info['count'], 'message' => '');
			}

			$votes_pro = Comment::changeRate($Comment->id);
			$result = array('success' => 1, 'data' => $votes_pro);

			/*
			$session = new CHttpSession;
			$session->open();

			if($data_id > 0 && $type != NULL && $session['user-rate-'.$data_id] == NULL){

				$Comment = Comment::model()->findByPk($data_id);

				if($Comment){

					if($type == 'top'){
						$action = '+';
					} else {
						$action = '-';
					}

					$votes_pro  = Comment::changeRate($Comment->id, $action);
					$result     = array('success' => 1, 'data' => $votes_pro);

					$session['user-rate-'.$data_id] = 1;
				}
			}
			*/
		}

		header('Content-type: application/json');
		echo json_encode($result);
	}
}