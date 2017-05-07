<?php

class C_membersController extends FrontEndController {

	public function urlRules(){
		return array(
			array('c_members/index',
				  'pattern'=>'{this}'
			),
			array('c_members/Unlogin',
				  'pattern'=>'{this}/unlogin/'
			),
			array('c_members/setVote',
				  'pattern'=>'{this}/set_vote/<member_id:\d+>/'
			),
			array('c_members/setPreVote',
				  'pattern'=>'{this}/set_pre_vote/<member_id:\d+>/'
			),
			array('c_members/index',
				  'pattern'=>'{this}/show/<code_name>/'
			),
			array('c_members/redirect',
				  'pattern'=>'{this}/<code_name>/'
			),
		);
	}

	public function actionUnlogin(){
		UsersAuth::logOut();
	}

	public function actionRedirect($code_name){
		$this->redirect('/');
	}

	public function actionsetPreVote($member_id){
		if(!Yii::app()->request->isAjaxRequest){
			throw new CHttpException(404);
		}

		$Members = Members::model()->findByPk($member_id);

		if(!$Members){
			throw new CHttpException(404);
		}

		$session = new CHttpSession;
		$session->open();
		$session['sziget_vote_member_id'] = $Members->id;

		header('Content-type: application/json');
		echo json_encode(array('success' => 1));
		exit;
	}

	public function actionSetVote($member_id){
		throw new CHttpException(404);

		if(!Yii::app()->request->isAjaxRequest){
			throw new CHttpException(404);
		}

		$out_array = array('success' => 0, 'message' => '');

		$Users = UsersAuth::isLogin();

		if(!$Users){
			$out_array['success'] =  -1;
			header('Content-type: application/json');
			echo json_encode($out_array);
			exit;
		}

		$Members = Members::model()->findByPk($member_id);

		if(!$Members){
			$out_array['message'] = 'Участник не найден';
			header('Content-type: application/json');
			echo json_encode($out_array);
			exit;
		}

		$session = new CHttpSession;
		$session->open();
		$session['sziget_vote_member_id'] = null;

		$MembersLikes = new MembersLikes((int)$member_id, (int)$Users->id);
		$result_likes = $MembersLikes->_set();

		if($result_likes){
			$out_array = array(
				'success' => 1,
				'vote_status' => $result_likes,
			);
		}

		$out_array['count_votes'] = $Members->updateLikes();

		header('Content-type: application/json');
		echo json_encode($out_array);
		exit;
	}

	/*
	 * if(GetRealIp() == '178.216.8.22')
	 */
	public function actionIndex($code_name = null, $page = 1){
		$Members = null;
		$html = null;
		$images_list = null;

		if(!empty($code_name)){

			$Members = Members::model()->published()->withCodeName($code_name)->find();

			if(!$Members){
				throw new CHttpException(404, 'Страница "'.$code_name.'" не найдена.');
			}

			//--------------------------------
			$Members->increaseView();
			//--------------------------------
			if ($Members->transfer->page_title == '') {
				$Members->transfer->page_title = $Members->transfer->name;
			}
			if ($Members->transfer->meta_description == '') {
				$Members->transfer->meta_description = $Members->transfer->description;
			}
			if ($Members->transfer->meta_keywords == '') {
				$Members->transfer->meta_keywords = $Members->transfer->name;
			}

			$this->pageTitle = $Members->transfer->page_title;
			$this->metaDescription = $Members->transfer->meta_description;
			$this->metaKeywords = $Members->transfer->meta_keywords;

			$this->og_title = $this->pageTitle;
			$this->og_desc = $this->metaDescription;

			if(!empty($Members->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Members::IMAGE_490x300.$Members->image_filename) ){
				$this->og_image = 'http://'.$_SERVER['HTTP_HOST'].Members::IMAGE_490x300.$Members->image_filename;
			}

			$html = $this->renderPartial(
				'view',
				array('Members' => $Members, 'ajax' => true),
				true);

			if(Yii::app()->request->isAjaxRequest){

				$out  = array('html' => $html);

				header('Content-type: application/json');
				echo json_encode($out);
				exit;
			} else {
				$membersItems = Members::getItems($page);
			}
		} else if(Yii::app()->request->isAjaxRequest){
			$limit = 6;

			$membersItems = Members::getItems($page, $limit);
			$membersCount = Members::model()->published()->count('image_filename != ""');

			$total_pages = ceil($membersCount / $limit);
			$remains = 0;

			if($total_pages > 1){
				$remains = $membersCount - ($page * $limit);
			}


			$html = '';

			if($membersItems){
				$html = $this->renderPartial('_items', array('membersItems' => $membersItems), true);
			}

			$out  = array('success' => 1, 'html' => $html, 'remains' => $remains);

			header('Content-type: application/json');
			echo json_encode($out);
			exit;
		} else {
			$membersItems = Members::getItems($page);

			$sql = 'SELECT image_filename FROM comma_members WHERE image_filename != "" AND active = 1';
			$images = Yii::app()->db->createCommand($sql)->queryAll();

			if(count($images)){
				foreach($images as $row){
					$images_list[] = Members::IMAGE_490x300 . $row['image_filename'];
				}

				$images_list = json_encode($images_list, JSON_HEX_QUOT | JSON_HEX_TAG);
			}
		}

		$this->render('index', array(
			'membersItems' => $membersItems,
			'Members' => $Members,
			'popup' => $html,
			'images_list' => $images_list,
		));
	}
}