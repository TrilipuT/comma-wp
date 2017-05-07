<?php

class SectionController extends FrontEndController {
	
	public function urlRules(){  
		return array(
			array('section/view',
				'pattern'=>'<code_name>/'
			), 

		);
	}

	public function actionView($code_name){
		$Section = Section::model()->published()->onlyDomain(Section::$real_domain_id)->withCodeName($code_name)->find();
		if(!$Section){
			throw new CHttpException(404, 'Страница "'.$code_name.'" не найдена.');
		}
		//--------------------------------------------------------------------------------
		$this->activeSection = $Section; 
		//--------------------------------------------------------------------------------
		if ($Section->transfer->page_title == '') {
			$Section->transfer->page_title = $Section->transfer->name;
		}
		if ($Section->transfer->meta_description == '') {
			$Section->transfer->meta_description = $Section->transfer->name;
		}
		if ($Section->transfer->meta_keywords == '') {
			$Section->transfer->meta_keywords = $Section->transfer->name;
		}

		if (!empty($Section->share_image) && file_exists($_SERVER['DOCUMENT_ROOT'] . Section::PATH_SHARE_IMAGE . $Section->share_image)) {
			$this->og_image = 'http://' . $_SERVER['HTTP_HOST'] . Section::PATH_SHARE_IMAGE . $Section->share_image;
		}
		//--------------------------------------------------------------------------------
		$this->breadcrumbs = array(
			$Section->transfer->name
		);

		$this->render('index', array('Section'	=> $Section ));
	}
	
}