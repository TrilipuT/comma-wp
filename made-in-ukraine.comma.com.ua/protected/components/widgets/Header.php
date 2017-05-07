<?php
class Header extends CWidget {

	public $activeSection;
	public $activeSubRubricsId;
	public $icon_class;
	public $activeRubric;

	public function run(){

		if(Yii::app()->language != 'ru')
			$language = '/'.Yii::app()->language;
		else
			$language = '';

		$searchUrl .= '/'.Base::findControllerAlias('C_search');

		$this->icon_class = Yii::app()->controller->icon_class;

		$sectionItems 	= Section::model()->published()->orderByOrderNum()->topMenu()->onlyDomain()->findAll();

		$rubricsItems  	= Rubrics::model()->published()->orderByOrderNum()->findAll('parent_id = 0 AND is_subsite = 0');
		$color 	 	    = '';
		$marging 		= '';
		$class 			= '';

		if($this->activeSection){

			if($this->activeSection instanceof Rubrics){
				$marging = 'add_bottom_margin';
				$Rubrics = $this->activeSection;
				$color   = 'background:'.$Rubrics->color.';';

				$subRubricsItems = Rubrics::model()->published()->orderByOrderNum()->findAll('parent_id = :parent_id', 
																							array(':parent_id' => $Rubrics->id)); 
			
			} else if($this->activeSection instanceof Section ) {

				$marging 		= 'add_bottom_margin';
				$ActiveSection 	= $this->activeSection; 

				if($ActiveSection->controller == 'C_blogsController.php')
					$class = 'header_red'; 
				else if($ActiveSection->controller == 'C_searchController.php'){

			        $Controller = Yii::app()->controller;
			 
					$searchCountAll      = $Controller->searchCountAll;
					$searchCountNews     = $Controller->searchCountNews;
					$searchCountArticles = $Controller->searchCountArticles;
					$searchCountBlogs    = $Controller->searchCountBlogs;
					$searchCountPhotos   = $Controller->searchCountPhotos;
					$searchCountVideos   = $Controller->searchCountVideos;
					
				} else if($ActiveSection->controller == 'C_tagController.php'){

					$class = 'header_lightgray'; 

				} else if($ActiveSection->controller == 'C_videosController.php'){


					$class 			= 'header_gray'; 
					$ActiveSection 	= $this->activeSection;  

					$subRubricsItems = VideoCats::model()->published()->orderByOrderNum()->findAll();
					
				} else if($ActiveSection->controller == 'C_authorsController.php'){

					$ActiveSection 	= $this->activeSection;  
				}
			}
		} 
 
		$this->render('header', array(	'rubricsItems' 			=> $rubricsItems,
										'color' 		 		=> $color,
										'class' 				=> $class,
										'marging' 	 			=> $marging,
										'Rubrics' 	 			=> $Rubrics,

										'ActiveSection' 		=> $ActiveSection,
										'subRubricsItems'		=> $subRubricsItems,
										'activeSubRubricsId'	=> $this->activeSubRubricsId,
										'sectionItems' 			=> $sectionItems,
										'searchCountAll'		=> $searchCountAll,
										'searchCountNews'		=> $searchCountNews,
										'searchCountArticles'	=> $searchCountArticles,
										'searchCountBlogs'		=> $searchCountBlogs,
										'searchCountPhotos'		=> $searchCountPhotos,
										'searchCountVideos'		=> $searchCountVideos,  

										'searchUrl' 			=> $searchUrl,
										));
	}
} 