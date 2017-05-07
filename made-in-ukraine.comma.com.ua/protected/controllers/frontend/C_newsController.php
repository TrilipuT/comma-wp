<?php

class C_newsController extends FrontEndController {

	public function urlRules(){
		return array(
            array('c_news/index',
				'pattern'=>'{this}'//\.html
			),
			array('c_news/view',
				'pattern'=>'{this}/<code_name>'//\.html
			),
		);
	} 
	 
	public function actionIndex($page = 1){ 
		
		$this->activeSection = $this->Section; 

	 	$newsItems = News::getItems($page); 

	 	if(Yii::app()->request->isAjaxRequest){
	 		
	 		$html = $this->renderPartial('_items', array('newsItems' => $newsItems), true);
	 		$out  = array('success' => 1, 
						  'html' 	=> $html,
						  'remains' => $newsItems['remains']);  
			//-------------
			header('Content-type: application/json'); 
        	echo json_encode($out); 
			exit;
	 	} else {
	 		$this->render('index', array('newsItems' => $newsItems ));
	 	} 
	} 

	public function actionView($code_name, $page = 1){
		
		$session=new CHttpSession;
		$session->open();   

	 	if($session['adminLook'] = 1){
	 		$News = News::model()->withCodeName($code_name)->find();  
	 	} else {
	 		$News = News::model()->published()->withCodeName($code_name)->find();  
	 	} 
		if(!$News) 
			throw new CHttpException(404, 'Страница "'.$code_name.'" не найдена.');   
		//--------------------------------
		$News->increaseView();
		//-------------------------------- 
		if ($News->transfer->page_title == '') {
			$News->transfer->page_title = $News->transfer->name;
		}
		if ($News->transfer->meta_description == '') {
			$News->transfer->meta_description = $News->transfer->name;
		}
		if ($News->transfer->meta_keywords == '') {
			$News->transfer->meta_keywords = $News->transfer->name;
		}

		$this->pageTitle 		= $News->transfer->page_title;
		$this->metaDescription  = $News->transfer->meta_description;
		$this->metaKeywords 	= $News->transfer->meta_keywords;
        
		$this->og_title         = $News->transfer->page_title; 
        $this->og_desc          = $News->transfer->meta_description;

        if(!empty($News->share_image) && file_exists($_SERVER['DOCUMENT_ROOT'].News::PATH_SHARE_IMAGE.$News->share_image) ){
            $this->og_image = 'http://'.$_SERVER['HTTP_HOST'].News::PATH_SHARE_IMAGE.$News->share_image;
        } else if(!empty($News->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].News::PATH_IMAGE.$News->image_filename) ){
            $this->og_image = 'http://'.$_SERVER['HTTP_HOST'].News::PATH_IMAGE.$News->image_filename;
        }

		//-----------------------------------------------------------------------------------------------------------------------  
        $lastNewsItems = News::model()->with('transfer:nameNoEmpty')->published()->orderByDateDesc()->limit(4)->findAll();
        //---------------------------------------------------------------------------------------------------------------------        
		$tags = CHtml::listData(NewsHasTegs::model()->withNews($News->id)->findAll(), 'id', 'teg_id'); 
		$tags = Tags::model()->with('transfer:nameNoEmpty')->orderByIdDesc()->published()->findAllByPk($tags);//->orderByOrderNum()
        //----------------------------------------------------------------------------------------------------------------------
		$otherItems = $News->getOther(); 
		//----------------------------------------------------------------------------------------------------------------------- 
		$this->render('view', array('News'  		=> $News,
									'lastNewsItems' => $lastNewsItems,
									'tags' 			=> $tags,
									'otherItems' 	=> $otherItems));
	}
}