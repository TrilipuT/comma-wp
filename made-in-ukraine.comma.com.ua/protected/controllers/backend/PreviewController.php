<?php
class PreviewController extends BackEndController{ 
	
  

 	public function init(){

 		$this->activeModule = 'preview';
		$this->moduleName 	= 'предпросмотр'; 

 		return true;
 	}
	 
	public function actionIndex($model_name, $id, $lang = 1){

 		$this->layout = 'application.views.frontend.layouts.main';
  	
  

		switch ($model_name) {
			case 'news':
		 
				$News = News::model()->withTransfer($lang)->findByPk($id); 
				if(!$News) 
					throw new CHttpException(404, 'Страница "'.$id.'" не найдена.');
				 
				//--------------------------------------------------------------------------------------------------	   
				$this->render('/../frontend/c_news/view',array( 'News' => $News, 'QuizQuestion' => $QuizQuestion, 'prev' => true ));

				break;
			case 'blogs' :

				$Article = Article::model()->findByPk($id, 'blog = 1');
				if(!$Article){
					throw new CHttpException(404, 'Страница "'.$code_name.'" не найдена.');    
				}

				$this->render('/../frontend/c_blogs/view',array('Article' 	 => $Article,
																'otherItems' => $otherItems, 
																'prev' 		 => true));

				break;

			case 'article' :
				$Article = Article::model()->findByPk($id, 'blog = 0');
				if(!$Article){
					throw new CHttpException(404, 'Страница "'.$code_name.'" не найдена.');    
				}

				$this->render('/../frontend/c_articles/view',array('Article' 		=> $Article, 
																	'tags'    		=> $tags,
																	'otherItems'	=> $otherItems,
																	'authorsItems'	=> $authorsItems, 
																	'prev' 		 	=> true ));

				break;

			default:
				exit;
				break;
		}
		
		//$this->render('index',array( 'settingsItems' => $settingsItems ));
	} 
}