<?php

class C_searchController extends FrontEndController {

	public function urlRules(){
		return array(
			array('c_search/index',
				'pattern'=>'{this}(/<alias:[0-9a-z_\-]+>)?'//\.html
			),
		);
	}
 
	public function actionIndex($alias='', $page=1){
		
		$this->activeSection 		= $this->Section;
		$this->activeSubRubricsId 	= $alias;  
		
        $q = trim(Yii::app()->request->getQuery('q'));
		$q = urldecode($q);

        $category   = trim($alias);
		 
		$result = Search::getSearchItema($q, $page, $category);

        $this->searchCountAll  		= $result['total'];  
		$this->searchCountNews 		= $result['searchCountNews'];
		$this->searchCountArticles 	= $result['searchCountArticles'];
		$this->searchCountBlogs  	= $result['searchCountBlogs'];   
		$this->searchCountPhotos 	= $result['searchCountPhotos'];
		$this->searchCountVideos 	= $result['searchCountVideos'];	 

		//--------------------------------------------------------------------------------    
		$this->render('index', array( 'result' 		=> $result['pageResult'], 
									  'resultLeft'	=> $result['resultLeft'],
									  'q'			=> $result['q'],
									  'total_pages' => $result['total_pages'],
									  'page' 		=> $result['page']));
	}
	
}