<?php

class C_blogsController extends FrontEndController {

	public function urlRules(){
		return array(
            array('c_blogs/index',
				'pattern'=>'{this}'//\.html
			),
			array('c_blogs/bloger',
				'pattern'=>'{this}/<bloger>'//\.html
			),
			array('c_blogs/view',
				'pattern'=>'{this}/<bloger>/<code_name>'//\.html
			) 
		);
	} 

	public function actionIndex(){ 
		
		$this->activeSection = $this->Section;
		//------------------------------------ 
 		//$blogerItems = Blogers::model()->published()->orderByOrderNum()->findAll(); 

 		$blogerItems = Blogers::getForMain(); 
 		//-------------------------------------------------------------------------
		$this->render('index', array('blogerItems' => $blogerItems ));
	}

	public function actionBloger($bloger = '', $page = 1){  
		 
		$Blogers = Blogers::model()->published()->withCodeName($bloger)->find();
		if(!$Blogers){
			throw new CHttpException(404, 'Страница "'.$bloger.'" не найдена.');    
		}

		$this->activeSection 	  = $this->Section;
		$this->activeSubRubricsId = $Blogers; 
 		//-------------------------------------------------------------------------
		$result = Article::getItems(array(), $Blogers->id, $page, 10);  
 		//-------------------------------------------------------------------------
 		if(Yii::app()->request->isAjaxRequest){

			$html = $this->renderPartial('_items', array('items' => $result['items'], 'i' => $flip+1), true); 
			$out  = array('success' => 1, 
						  'html' 	=> $html,
						  'remains' => $result['remains']);  
			//-------------
			header('Content-type: application/json'); 
        	echo json_encode($out); 
			exit;
		} else {
			$this->render('bloger', array('result' => $result ));
		} 
	}

	public function actionView($bloger, $code_name){  

		 
		$session=new CHttpSession;
		$session->open();   

		if(!$session['adminLook'] = 1){
	 		$Blogers = Blogers::model()->published()->withCodeName($bloger)->find(); 
		} else {
			$Blogers = Blogers::model()->withCodeName($bloger)->find(); 
		} 

		if(!$Blogers){
			throw new CHttpException(404, 'Страница "'.$bloger.'" не найдена.');    
		}
		//-------------------------------------------------------------------------
		$this->activeSection 	  = $this->Section;
		//$this->activeSubRubricsId = $Blogers; 
 		//-------------------------------------------------------------------------

		if(!$session['adminLook']){
	 		$Article = Article::model()->published()->withCodeName($code_name)->find('blog = 1');
	 	} else {
	 		$Article = Article::model()->withCodeName($code_name)->find('blog = 1');
	 	}

		if(!$Article){
			throw new CHttpException(404, 'Страница "'.$code_name.'" не найдена.');    
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

		$this->pageTitle 		= $Article->transfer->page_title;
		$this->metaDescription  = $Article->transfer->meta_description;
		$this->metaKeywords 	= $Article->transfer->meta_keywords;
        
		$this->og_title         = $Article->transfer->page_title; 
        $this->og_desc          = $Article->transfer->meta_description;

        if(!empty($Article->share_image) && file_exists($_SERVER['DOCUMENT_ROOT'].Article::PATH_SHARE_IMAGE.$Article->share_image) ){
            $this->og_image = 'http://'.$_SERVER['HTTP_HOST'].Article::PATH_SHARE_IMAGE.$Article->share_image;
        } else if(!empty($Article->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Article::PATH_IMAGE.$Article->image_filename) ){
            $this->og_image = 'http://'.$_SERVER['HTTP_HOST'].Article::PATH_IMAGE.$Article->image_filename;
        } else if(!empty($Blogers->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Blogers::IMAGE_300x300.$Blogers->image_filename) ){
            $this->og_image = 'http://'.$_SERVER['HTTP_HOST'].Blogers::IMAGE_300x300.$Blogers->image_filename;
        }

        //-------------------------------- 
        $otherItems = $Article->getOther(1);  
        //---------------------------------------------------------------------------------------------------------------------        
		$tags = CHtml::listData(ArticleHasTegs::model()->withArticle($Article->id)->findAll(), 'id', 'teg_id'); 
        $tags = Tags::model()->with('transfer:nameNoEmpty')->orderByIdDesc()->published()->findAllByPk($tags);//->orderByOrderNum()
        //------------------------------
		$this->render('view', array('Article' 	 => $Article,
									'tags' 		 => $tags,
 									'otherItems' => $otherItems));
	}

}