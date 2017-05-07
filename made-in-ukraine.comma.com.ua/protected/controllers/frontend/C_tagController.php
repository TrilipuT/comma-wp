<?php

class C_tagController extends FrontEndController {

	public function urlRules(){
		return array(
			array('c_tag/index',
				'pattern'=>'{this}/<tag>(/<alias:[0-9a-z_\-]+>)?'//\.html
			),
		);
	}
 
	public function actionIndex($tag, $alias='', $page=1){
		  
		$this->activeSection 		= $this->Section;
		$this->activeSubRubricsId 	= $alias; 

		//$q 		= Yii::app()->request->getQuery('q');
		$q 			= $tag;
		$category 	= trim($alias);
        $result 	= array();
        $onPage 	= 20;

  

		if (trim($q) != '') {
			if ($category == '' || $category == 'news') {
				$news 		= News::model()->published()->searchByTag($q)->orderByDateDesc()->findAll();
				$news_num 	= count($news);
			} else {
				$news 		= array();
				$news_num 	= News::model()->published()->searchByTag($q)->count();
			}
			//---------------------------------------------
			if ($category == '' || $category == 'videos') {
				$videos 	= Videos::model()->published()->searchByTag($q)->orderByDateDesc()->findAll();
				$videos_num = count($videos);
			} else {
				$videos 	= array();
				$videos_num = Videos::model()->published()->searchByTag($q)->count();
			} 
			//---------------------------------------------
			if ($category == '' || $category == 'photos') {
				$photos 	= Gallery::model()->published()->searchByTag($q)->orderByDateDesc()->findAll();
				$photos_num = count($photos);
			} else {
				$photos 	= array();
				$photos_num = Gallery::model()->published()->searchByTag($q)->count();
			} 
			//---------------------------------------------
			if ($category == '' || $category == 'articles') {  
				$articles 	  = Article::model()->published()->searchByTag($q)->orderByDateDesc()->findAll('blog = 0');
				$articles_num = count($articles);
			} else {
				$articles 	  = array();
				$articles_num = Article::model()->published()->searchByTag($q)->count('blog = 0');
			} 
			//---------------------------------------------
			if ($category == '' || $category == 'blogs') {  
				$blogs 	   = Article::model()->published()->searchByTag($q)->orderByDateDesc()->findAll('blog = 1');
				$blogs_num = count($blogs);
			} else {
				$blogs 	   = array();
				$blogs_num = Article::model()->published()->searchByTag($q)->count('blog = 1');
			} 

			//-----------------------------------
			foreach($news as $News){
	            $result[] = $News;
	        }
	        foreach($videos as $Videos){
	            $result[] = $Videos;
	        }
	        foreach($photos as $Gallery){
	            $result[] = $Gallery;
	        }
	        foreach($articles as $Article){
	            $result[] = $Article;
	        }
	        foreach($blogs as $Article){
	            $result[] = $Article;
	        }
	        //-----------------------------------
			
		} else {
			$news 		= array();
			$news_num 	= 0;
			//---------------
			$videos 	= array();
			$videos_num = 0;
			//---------------
			$photos 	= array();
			$photos_num = 0;
			//---------------
			$articles 		= array();
			$articles_num 	= 0;
			//---------------
			$blogs 		= array();
			$blogs_num 	= 0;
		}  
        //----------------------------------------------------------------
        $resultOffset = ($page-1)*$onPage;
        $pageResult = array_slice($result, $resultOffset, $onPage);
        $resultLeft = count($result)-$page*$onPage;
        
		$all_num = $news_num + $videos_num + $photos_num + $articles_num + $blogs_num;   
		//--------------------------------------------------------------------------------    

		$this->searchCountAll  		= $all_num;
		$this->searchCountNews 		= $news_num;
		$this->searchCountArticles 	= $articles_num;
		$this->searchCountBlogs  	= $blogs_num;   
		$this->searchCountPhotos 	= $photos_num;
		$this->searchCountVideos 	= $videos_num;	

		//--------------------------------------------------------------------------------    
		$this->render('index', array( 'result' 		=> $pageResult, 
									  'resultLeft'	=> $resultLeft,
									  'q'			=> $q));
}
	
}