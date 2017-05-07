<?php

class C_articlesController extends FrontEndController {
	public function urlRules() {
		return array(
			array('c_articles/index',
				  'pattern' => '{this}/<code_name>(/<subCodeName>)?' //\.html
			)
		);
	}

	public function actionUkraine() {
		$this->og_title = 'How to make it in Ukraine';
		$this->pageTitle = 'How to make it in Ukraine';

		return $this->actionIndex("made_in_ukraine", '', 1, 0);
	}

	public function actionIndex($code_name, $subCodeName = '', $page = 1, $flip = 0) {

		$rubrics = array();
		$Rubrics = Rubrics::model()->published()->withCodeName($code_name)->find();
		if (!$Rubrics) {
			$this->article($code_name);
			exit;
		}
		$view = 'index';

		if ($code_name == 'made_in_ukraine') {
			$view = 'ukraine_index';
			$this->layout = 'ukraine';
			$r = Rubrics::model()->published()->withCodeName("ukraine_objects")->find();
			$this->og_desc = strip_tags($r->transfer->description);
			$this->metaDescription = strip_tags($r->transfer->description);
		}

		$this->activeSection = $Rubrics;
		//------------------------------------
		if ($Rubrics->parent_id == 0) {
			$subRubricsItems = Rubrics::model()->published()->orderByOrderNum()->findAll('parent_id = :parent_id',
				array(':parent_id' => $Rubrics->id));
			if ($subRubricsItems) {
				foreach ($subRubricsItems as $_Rubrics) {
					$rubrics[] = $_Rubrics->id;
				}
			}
		}
		$this->icon_class = $Rubrics->color_class;
		//------------------------------------ 
		if ($subCodeName != '') {
			$SubRubrics = Rubrics::model()->published()->withCodeName($subCodeName)->find('parent_id = :parent_id', array(':parent_id' => $Rubrics->id));
			if (!$SubRubrics) {
				throw new CHttpException(404, 'Страница "' . $subCodeName . '" не найдена.');
			}

			$this->activeSubRubricsId = $SubRubrics->id;
			$rubrics = array($SubRubrics->id);
		}
		//------------------------------------------------------------------------- 
		/*$criteria               = new CDbCriteria;  
        $criteria->select       = 't.*';
        $criteria->condition    = 't.active = 1 AND t.datetime <= NOW()';  
        $criteria->limit 		= 4;
		$criteria->offset 		= 0;
		$criteria->order 		= 't.datetime DESC, t.order_num, t.id';
		$newMaterials 			= Article::model()->with('transfer:nameNoEmpty')->findAll($criteria);   */
		//-------------------------------------------------------------------------
		$result = Article::getItems($rubrics, 0, $page, 14);
		//-------------------------------------------------------------------------
		if (Yii::app()->request->isAjaxRequest) {

			$html = $this->renderPartial('_items', array('items' => $result['items'], 'i' => $flip + 1), true);
			$out = array('success' => 1,
						 'html' => $html,
						 'remains' => $result['remains']);
			//-------------
			header('Content-type: application/json');
			echo json_encode($out);
			exit;
		} else {
			$this->render($view, array('Rubrics' => $Rubrics,
									   'result' => $result,
									   'newMaterials' => $newMaterials));
		}
	}

	public function article($code_name) {

		//if(GetRealIp() == "178.216.8.28"){
		$session = new CHttpSession;
		$session->open();

		if ($session['adminLook'] = 1) {
			$Article = Article::model()->withCodeName($code_name)->find('blog = 0');
		} else {
			$Article = Article::model()->published()->withCodeName($code_name)->find('blog = 0');
		}

		if (!$Article) {
			throw new CHttpException(404, 'Страница "' . $code_name . '" не найдена.');
		}

		$Rubrics = Rubrics::model()->findByPk($Article->rubric_id);
		if ($Rubrics) {
			if ($Rubrics->parent_id == 0) {
				$this->activeRubric = $Rubrics->id;
			} else {
				$this->activeRubric = $Rubrics->parent_id;
			}
		}
		//--------------------------------
		$Article->increaseView();
		//-------------------------------- 
		if ($Article->transfer->page_title == '') {
			$Article->transfer->page_title = $Article->transfer->name;
		}
		if ($Article->transfer->meta_description == '') {
			$Article->transfer->meta_description = $Article->transfer->name;
		}
		if ($Article->transfer->meta_keywords == '') {
			$Article->transfer->meta_keywords = $Article->transfer->name;
		}

		$view = 'view';
		$main_rubric = Rubrics::model()->findByPk($this->activeRubric);
		if ($main_rubric->code_name == 'made_in_ukraine') {
			$view = 'ukraine_view';
			$this->layout = 'ukraine';

			// redirect to made-in-ukraine subdomain if requested without it
			if (!strpos(Yii::app()->request->hostInfo, 'made-in-ukraine')) {
				return $this->redirect("http://made-in-ukraine.comma.com.ua" . Yii::app()->request->url);
			}
		}

		$this->pageTitle = $Article->transfer->page_title;
		$this->metaDescription = $Article->transfer->meta_description;
		$this->metaKeywords = $Article->transfer->meta_keywords;

		$this->og_title = $Article->transfer->page_title;
		$this->og_desc = $Article->transfer->meta_description;

		if (!empty($Article->share_image) && file_exists($_SERVER['DOCUMENT_ROOT'] . Article::PATH_SHARE_IMAGE . $Article->share_image)) {
			$this->og_image = 'http://' . $_SERVER['HTTP_HOST'] . Article::PATH_SHARE_IMAGE . $Article->share_image;
		} else if (!empty($Article->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'] . Article::PATH_IMAGE . $Article->image_filename)) {
			$this->og_image = 'http://' . $_SERVER['HTTP_HOST'] . Article::PATH_IMAGE_SRC . $Article->image_filename;
		}

		if ($id = Yii::app()->request->getParam('image')) {
			$gallery = Gallery::model()->findByPk($id);
			$this->og_image = 'http://' . $_SERVER['HTTP_HOST'] . Gallery::PATH_IMAGE . $gallery->image_filename;
			$this->og_title = $gallery->transfer->name;
			$this->og_desc = $gallery->transfer->description;
			$this->metaDescription = $gallery->transfer->meta_description;
			$this->metaKeywords = $gallery->transfer->meta_keywords;
		}
		//---------------------------------------------------------------------------------------------------------------------        
		$tags = CHtml::listData(ArticleHasTegs::model()->withArticle($Article->id)->findAll(), 'id', 'teg_id');
		$tags = Tags::model()->with('transfer:nameNoEmpty')->orderByIdDesc()->published()->findAllByPk($tags); //->orderByOrderNum()
		//----------------------------------------------------------------------------------------------------------------------
		$otherItems = $Article->getOther();
		//----------------------------------------------------------------------------------------------------------------------
		$list = CHtml::listData(ArticleHasAuthors::model()->withArticle($Article->id)->findAll(), 'id', 'authors_id');
		$authorsItems = Authors::model()->published()->orderByOrderNum()->findAllByPk($list);
		//----------------------------------------------------------------------------------------------------------------------
		$this->render($view, array('Article' => $Article,
								   'tags' => $tags,
								   'otherItems' => $otherItems,
								   'authorsItems' => $authorsItems));
	}

}