<?php

class C_galleryController extends FrontEndController {
	public function urlRules() {
		return array(

			array('c_gallery/view',
				  'pattern' => '{this}/<code_name:\w+>/'
			),
			array('c_gallery/index',
				  'pattern' => '{this}(/<author>)?/'
			)
		);
	}

	public function actionIndex($author = '', $page = 1) {

		$this->activeSection = $this->Section;
		//-------------------------------------------------------------------------
		$author_id = 0;
		if ($author != '') {
			$Authors = Authors::model()->published()->withCodeName($author)->find('t.photographer = 1');
			if (!$Authors) {
				throw new CHttpException(404, 'Страница "' . $author . '" не найдена.');
			}

			$author_id = $Authors->id;
			$this->activeSubRubricsId = $Authors;
		}
		//-------------------------------------------------------------------------
		$result = Gallery::getItems($author_id, $page, 9);
		//-------------------------------------------------------------------------
		if (Yii::app()->request->isAjaxRequest) {

			$html = $this->renderPartial('_items', array('items' => $result['items'], 'i' => $flip + 1, 'big_img' => true), true);
			$out = array('success' => 1,
						 'html' => $html,
						 'remains' => $result['remains']);
			//-------------
			header('Content-type: application/json');
			echo json_encode($out);
			exit;
		} else {
			$this->render('index', array('result' => $result));
		}
	}

	public function actionView($code_name) {

		$Gallery = Gallery::model()->with(array('photos:orderByOrderNum'))->published()->withCodeName($code_name)->find();
		//$Gallery = Gallery::model()->with(array('photos:orderByOrderNum'))->published()->findByPk($id);
		if (!$Gallery) {
			$Authors = Authors::model()->published()->withCodeName($code_name)->find('t.photographer = 1');
			if ($Authors) {

				$this->actionIndex($code_name);
				exit;
			}

			throw new CHttpException(404, 'Страница "' . $code_name . '" не найдена.');
		}
		//--------------------------------
		$Gallery->increaseView();
		//-------------------------------- 
		if ($Gallery->transfer->page_title == '') {
			$Gallery->transfer->page_title = $Gallery->transfer->name;
		}
		if ($Gallery->transfer->meta_description == '') {
			$Gallery->transfer->meta_description = $Gallery->transfer->name;
		}
		if ($Gallery->transfer->meta_keywords == '') {
			$Gallery->transfer->meta_keywords = $Gallery->transfer->name;
		}

		$this->pageTitle = $Gallery->transfer->page_title;
		$this->metaDescription = $Gallery->transfer->meta_description;
		$this->metaKeywords = $Gallery->transfer->meta_keywords;

		$this->og_title = $Gallery->transfer->page_title;
		$this->og_desc = $Gallery->transfer->meta_description;

		if (!empty($Gallery->share_image) && file_exists($_SERVER['DOCUMENT_ROOT'] . Gallery::PATH_SHARE_IMAGE . $Gallery->share_image)) {
			$this->og_image = 'http://' . $_SERVER['HTTP_HOST'] . Gallery::PATH_SHARE_IMAGE . $Gallery->share_image;
		} else if (!empty($Gallery->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'] . Gallery::PATH_IMAGE . $Gallery->image_filename)) {
			$this->og_image = 'http://' . $_SERVER['HTTP_HOST'] . Gallery::PATH_IMAGE . $Gallery->image_filename;
		}
		//---------------------------------------------------------------------------------------------------------------------        
		$tags = CHtml::listData(GalleryHasTegs::model()->withGallery($Gallery->id)->findAll(), 'id', 'teg_id');
		$tags = Tags::model()->with('transfer:nameNoEmpty')->orderByIdDesc()->published()->findAllByPk($tags); //->orderByOrderNum()
		//---------------------------------------------------------------------------------------------------------------------- 
		$list = CHtml::listData(GalleryHasAuthors::model()->withGallery($Gallery->id)->findAll(), 'id', 'authors_id');
		$authorsItems = Authors::model()->published()->orderByOrderNum()->findAllByPk($list);
		//----------------------------------------------------------------------------------------------------------------------
		$this->render('view', array('Gallery' => $Gallery,
									'tags' => $tags,
									'authorsItems' => $authorsItems));
	}

}