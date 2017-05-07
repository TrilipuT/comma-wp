<?php

class SupportController extends BackEndController {
	/*
	public function init(){

		if(Yii::app()->user->id) 
			   $this->role = Editors::model()->getUserRole(Yii::app()->user->id);

		if($this->role == 'superAdmin'){

			   //$this->setAction(1);

			   echo '<pre>'; var_dump($this->getAction(), Yii::app()->user->checkAccess('create')); echo '</pre>'; //actions getAction  setAction

			   exit;
		   }

	}*/

	public function actionIndex($model_name = '', $page = 1) {

		if (!$model_name) {
			$this->render('index');
		} else {

			$this->activeModule = $model_name;

			$className = ucfirst($model_name);
			$class = new $className();
			$on_page = 20;

			$criteria = new CDbCriteria;
			$criteria->order = 't.id DESC';

			if (isset($class->datetime)) {
				$criteria->order = 't.datetime DESC';
			} else if (isset($class->order_num)) {
				$criteria->order = 't.order_num';
			}

			switch ($model_name) {
				case 'section':
					$criteria->order = 't.domain_id, t.order_num';
					break;
				case 'article':
					$criteria->condition = 't.blog = 0';
					$criteria->order = 't.datetime DESC, t.order_num';
					break;
				case 'blogs':
					$criteria->condition = 't.blog = 1';
					$criteria->order = 't.datetime DESC, t.order_num';
					break;
				case 'news':
					$criteria->order = 't.datetime DESC, t.order_num';
					break;
				case 'rubrics':
					$criteria->condition = 't.parent_id = 0';
					$criteria->order = 't.order_num';
					break;

				case 'tags':
					$criteria->with = array('transfer');
					$criteria->order = 'transfer.name';
					break;

				case 'banners':
					$banner = (int)Yii::app()->request->getParam('banner', 0);
					$test_show = (int)Yii::app()->request->getParam('test_show', 0);

					if($banner > 0 && $test_show == 1){
						$cookie = new CHttpCookie('banner_test_show', $banner);
						$cookie->expire = time()+300; // 5 минут
						Yii::app()->request->cookies['banner_test_show'] = $cookie;

						$this->redirect('/support/banners/');
						exit;
					}

					$criteria->order = 'id DESC, order_num';
					break;
			}

			$list = $className::model()->page($page, $on_page)->findAll($criteria);
			$count_all = $className::model()->count($criteria);

			//-----------------------------------------------------------------------------------------------------------------------
			$dop_link = '';

			if ($model_name == 'comment') {
				$user_id = (int)Yii::app()->request->getParam('user_id', 0);

				if ($user_id > 0) {
					$dop_link = '/?news_id=' . $user_id;
					$list = $className::model()->withUser($user_id)->page($page, $on_page)->findAll($criteria);
					$count_all = $className::model()->withUser($user_id)->count($criteria);
				}
			}

			//-----------------------------------------------------------------------------------------------------------------------
			$total_pages = ceil($count_all / $on_page);

			if ($_SERVER['QUERY_STRING'] != '') {
				if ($dop_link != '') {
					$dop_link .= '&' . $_SERVER['QUERY_STRING'];
				} else {
					$dop_link = '/?' . $_SERVER['QUERY_STRING'];
				}
			}

			$this->render('/layouts/main_index', array('list' => $list,
													   'page' => $page,
													   'total_pages' => $total_pages,
													   'model_name' => $model_name,
													   'dop_link' => $dop_link));

		}
	}

	private function getTransfersAttr($post) {

		$attrs = array();

		if (count($post) > 0) {
			foreach ($post as $attr => $valuesArray) {

				if (count($valuesArray) > 0) {

					foreach ($valuesArray as $lang_id => $value) {

						$attrs[$lang_id][$attr] = $value;
					}
				}
			}
		}

		return $attrs;
	}

	public function actionCreate($model_name) {

		$params = '';
		if ($_SERVER['QUERY_STRING'] != '') {
			$params = '/?' . $_SERVER['QUERY_STRING'];
		}

		$this->activeModule = $model_name;

		$className = ucfirst($model_name);
		$model = new $className;

		if ($model->transfer_type) { // если у модуля есть переводы
			$transferClassName = $className . 'Transfer';
			$model_transfer = new $transferClassName;
		}

		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);

		switch ($model_name) {
			case 'gallery':
			case 'article':
			case 'blogs':
			case 'videos':
			case 'news':
			case 'jury':
			case 'members':

				$model->active = 0;

				if ($model->save()) {
					if ($model->transfer_type) {
						if ($this->languageList) {

							foreach ($this->languageList as $lang_id => $lang_name) {

								$model_transfer = new $transferClassName;
								$model_transfer->parent_id = $model->id;
								$model_transfer->language_id = $lang_id;
								$model_transfer->save();
							}
						}
					}

					$this->redirect('/support/update/' . $model_name . '/' . $model->id . $params);
				}

				break;
		}

		$post = Yii::app()->request->getPost($className);
		if ($post) {

			$model->attributes = $_POST[$className];

			if ($model->save()) {

				if ($model->transfer_type) { // если у модуля есть переводы

					$postTransfer = Yii::app()->request->getPost($transferClassName);
					if ($postTransfer) {

						$postTransfer = $this->getTransfersAttr($postTransfer);

						if ($this->languageList) {

							foreach ($this->languageList as $lang_id => $lang_name) {

								$model_transfer = new $transferClassName;

								$model_transfer->parent_id = $model->id;

								$model_transfer->language_id = $lang_id;
								$model_transfer->attributes = $postTransfer[$lang_id];
								$model_transfer->save();
							}
						}
					}
				}
				// end transfer

				$this->redirect(array('support/' . $model_name . $params));
			}
		}

		$this->render('create', array('model' => $model,
									  'model_name' => $model_name,
									  'model_transfer' => $model_transfer,
									  'dop_link' => $params
		));

	}

	public function actionUpdate($model_name, $id) {

		$this->activeModule = $model_name;

		$className = ucfirst($model_name);
		$model = $this->loadModel($className, $id);

		if ($model->transfer_type) { // если у модуля есть переводы

			$transferClassName = $className . 'Transfer';
			$model_transfer = new $transferClassName;
		}

		$post = Yii::app()->request->getPost($className);
		if (isset($post)) {

			$model->attributes = $post;

			if (isset($model->blocked)) {
				$model->blocked = 0;
			}

			if ($model->save() && !$model->getErrors()) {

				if ($model->transfer_type) { // если у модуля есть переводы

					$postTransfer = Yii::app()->request->getPost($transferClassName);
					if ($postTransfer) {

						$postTransfer = $this->getTransfersAttr($postTransfer);

						if ($this->languageList) {

							foreach ($this->languageList as $lang_id => $lang_name) {

								$model_transfer = $this->loadTransferModel($transferClassName, $model->id, $lang_id);
								$model_transfer->attributes = $postTransfer[$lang_id];
								$model_transfer->save();
							}
						}
					}
				} // end transfer

				$params = '';
				if ($_SERVER['QUERY_STRING'] != '') {
					$params = '/?' . $_SERVER['QUERY_STRING'];
				}

				//предпросмотрт 
				$preview = (int)Yii::app()->request->getParam('preview', 0);
				if ($preview) {
					session_start();
					$session = new CHttpSession;
					$session->open();
					$_SESSION['adminLook'] = $session['adminLook'] = true;

					$this->redirect($model->getItemUrl());
					//$this->redirect(array('support/preview/'.$model_name.'/'.$model->id.'/?lang=1'));
				} else {
					$this->redirect(array('support/' . $model_name . $params));
				}
			} else {

				//var_dump($model->getErrors()); exit;
			}
		} else {
			//пока только для новости
			if ($model_name == 'news') {
				//$model->blocked = 1;
				// $model->update  = true;
				// $model->update(array('blocked', 'modified_by'));
			}
		}

		$this->render('update', array(
			'model' => $model,
			'model_name' => $model_name,
			'model_transfer' => $model_transfer,
			'dop_link' => $params
		));
	}

	public function actionUnlocked($model_name, $id) {

		$className = ucfirst($model_name);
		$model = $this->loadModel($className, $id);

		if ($model_name == 'news') {

			if (isset($model->update)) {
				$model->update = 1;
			}

			if (isset($model->blocked)) {
				$model->blocked = 0;
				$model->update(array('blocked'));
			}
		}

		$params = '';
		if ($_SERVER['QUERY_STRING'] != '') {
			$params = '/?' . $_SERVER['QUERY_STRING'];
		}

		$this->redirect(array('support/' . $model_name . $params));
	}

	public function actionDelete($model_name, $id) {

		//if(Yii::app()->request->isPostRequest)
		//{
		// we only allow deletion via POST request
		$className = ucfirst($model_name);
		$this->loadModel($className, $id)->delete();

		$params = '';
		if ($_SERVER['QUERY_STRING'] != '') {
			$params = '/?' . $_SERVER['QUERY_STRING'];
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('support/' . $model_name . $params));
		}
		//}
		//else
		//	throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function loadTransferModel($className, $parent_id, $lang_id) {

		$className = $className;
		$model = $className::model()->findByParent($parent_id, $lang_id);

		if ($model === null) {
			$model = new $className;
			$model->parent_id = $parent_id;
			$model->language_id = $lang_id;
			$model->save();

		}

		return $model;
	}


	/*
	protected function performAjaxValidation($model){

		if(isset($_POST['ajax']) && $_POST['ajax']==='mail_groups-form'){

			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	*/

}