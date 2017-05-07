<?php

class C_authorsController extends FrontEndController {

	public function urlRules(){
		return array(
            array('c_authors/index',
				'pattern'=>'{this}'//\.html
			),
			array('c_authors/photographers',
				'pattern'=>'{this}/photographers/?'//\.html
			)  ,
			array('c_authors/author',
				'pattern'=>'{this}/<author>'//\.html
			)
		);
	} 

	public function actionIndex(){ 
		
		$this->activeSection = $this->Section;
		//------------------------------------
 		$authorsItems = Authors::model()->published()->with('transfer')->findAll(array('condition' => 't.photographer = 0', 'order' => 'transfer.last_name')); 
 		//-------------------------------------------------------------------------
		$this->render('index', array('authorsItems' => $authorsItems ));
	}

	public function actionPhotographers(){ 
		
		$this->activeSection = $this->Section;
		//------------------------------------
 		$authorsItems = Authors::model()->published()->with('transfer')->findAll(array('condition' => 't.photographer = 1', 'order' => 'transfer.last_name')); 
 		//-------------------------------------------------------------------------
		$this->render('photographers', array('authorsItems' => $authorsItems ));
	}

	public function actionAuthor($author = '', $page = 1){  
		
		$Authors = Authors::model()->published()->withCodeName($author)->find();
		if(!$Authors){
			throw new CHttpException(404, 'Страница "'.$author.'" не найдена.');    
		}

		$this->activeSection 	  = $this->Section;
		$this->activeSubRubricsId = $Authors; 
 		//-------------------------------------------------------------------------
 		$articleItems = Article::getItemsForMain($Authors->id, 0, $page); 
 		$colsArray    = array();  
 		if($articleItems['items']){  

            $col = 0;
            foreach ($articleItems['items'] as $key => $Article) {
                  
                //если это блог, то пропускаем
                if($Article->blog || $Article->bloger_id > 0){
                    continue;
                }

                $colsArray[$col][] = $Article;

                $col++;
                if($col == 3){
                    $col = 0;
                } 
            }// endforeach
        } 
 		//-------------------------------------------------------------------------
		$this->render('author', array('colsArray' => $colsArray, 'articleItems' => $articleItems));
	}  
}