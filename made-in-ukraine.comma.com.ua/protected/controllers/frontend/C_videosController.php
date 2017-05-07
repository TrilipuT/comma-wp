<?php

class C_videosController extends FrontEndController {

	public function urlRules(){
		return array(
            array('c_videos/index',
				'pattern'=>'{this}(/<code_name>)?'//\.html
			), 
		);
	} 

	public function actionIndex($code_name='', $page = 1, $offset = 0){ 
		
		$this->activeSection = $this->Section; 

		if($code_name != ''){
			$VideoCats = VideoCats::model()->published()->withCodeName($code_name)->find();
			if(!$VideoCats){ 
				$this->videos($code_name);
				exit; 
			}
			$this->activeSubRubricsId = $VideoCats->id;


			//-------------
            /*
			if($page == 1){
				$start = 0;
				$limit = 7;
				$_page = 1;
			} else {
				$_page  = $page-1;
				$start = 7;
				$limit = 8;
			}
            */
            if($page == 1){
                $start = 0;
                $limit = 7;
                $_page = 1;
            } else {
                $_page = $page-1;
                $limit = 8;
                $start = intval($offset);
            }
			//-------------------------------------------------------- 
			if(Yii::app()->request->isAjaxRequest){
				//$videosItems = Videos::getVideos($VideoCats->id, $_page+1, $limit, $offset);  
				//$start = $offset-1;
			} 

			$videosItems = Videos::getVideos($VideoCats->id, 0, $_page, $limit, $start);

            if(GetRealIp() == "178.216.8.22" ){
                 //var_dump($videosItems['total']);
            }
			//--------------
			if($start == 0){
				$start = $limit;
			}
			$result[$VideoCats->id] = array('cat' => $VideoCats, 'videos' => $videosItems, 'start' => $start);
		} else { 
			//--------
			$videoCatsItems = VideoCats::model()->published()->orderByOrderNum()->findAll();
			if($videoCatsItems){

				$result = array();
				foreach ($videoCatsItems as $key=>$VideoCats) { 


					if($key == 0){
						if($page == 1){
							$_page = 1;
							$start = 0;
							$limit = 7;
						} else {
							$_page  = $page-1;
							$start = 7;
							$limit = 6;
						}
					} else {
						if($page == 1){
							$_page = 1;
							$start = 0;
							$limit = 9;
						} else {
							$_page  = $page-1;
							$start = 9;
							$limit = 8;
						}
					}


                    $videosItems = Videos::getVideos($VideoCats->id, 0, $_page, $limit, $start);
                    if($start == 0){
						$start = $limit;
					}
					$result[$VideoCats->id] = array('cat' => $VideoCats, 'videos' => $videosItems, 'start' => $start);
				}// end foreach
			}
		}
		//------------------------------------------------------------------------
		//найдем что осталось еще
		$remains 	 = 0; 
		$total_pages = 0;
		if(count($result) > 0){
			foreach ($result as $key => $item) {
				$remains = $remains+$item['videos']['remains'];

				if($item['videos']['total_pages'] > $total_pages){
					$total_pages = $item['videos']['total_pages'];
				}
			}
		}  
		//------------------------------------------------------------------------
		if(Yii::app()->request->isAjaxRequest){


			$html = $this->renderPartial('_items', array("i" => 0,'item' => $result[$VideoCats->id]), true);
			$out  = array('success' => 1, 
						  'html' 	=> $html,
						  'remains' => $result[$VideoCats->id]['videos']['remains']);  
			//-------------
			header('Content-type: application/json'); 
        	echo json_encode($out); 
			exit;
		} else {
			$this->render('index', array('result' 		=> $result,
										 'remains' 		=> $remains,
										 'total_pages'  => $total_pages,
										 'page' 		=> $page ));
		} 
	}

	public function videos($code_name){  
		
		$session=new CHttpSession;
		$session->open();

 		if($session['adminLook'] = 1){
	 		$Videos = Videos::model()->withCodeName($code_name)->find();
	 	} else {
	 		$Videos = Videos::model()->published()->withCodeName($code_name)->find();
	 	} 

		if(!$Videos){
			throw new CHttpException(404, 'Страница "'.$code_name.'" не найдена.');    
		}
		//--------------------------------
		$VideoCats = VideoCats::model()->published()->findByPk($Videos->category_id);
		if($VideoCats){
			$this->activeSubRubricsId = $VideoCats->id;
		}
		//--------------------------------
		$Videos->increaseView();
		//-------------------------------- 
		if ($Videos->transfer->page_title == '') {
			$Videos->transfer->page_title = $Videos->transfer->name;
		}
		if ($Videos->transfer->meta_description == '') {
			$Videos->transfer->meta_description = $Videos->transfer->name;
		}
		if ($Videos->transfer->meta_keywords == '') {
			$Videos->transfer->meta_keywords = $Videos->transfer->name;
		}

		$this->pageTitle 		= $Videos->transfer->page_title;
		$this->metaDescription  = $Videos->transfer->meta_description;
		$this->metaKeywords 	= $Videos->transfer->meta_keywords;
        
		$this->og_title         = $Videos->transfer->page_title; 
        $this->og_desc          = $Videos->transfer->meta_description;

        if(!empty($Videos->share_image) && file_exists($_SERVER['DOCUMENT_ROOT'].Videos::PATH_SHARE_IMAGE.$Videos->share_image) ){
            $this->og_image = 'http://'.$_SERVER['HTTP_HOST'].Videos::PATH_SHARE_IMAGE.$Videos->share_image;
        } else if(!empty($Videos->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Videos::PATH_IMAGE.$Videos->image_filename) ){
            $this->og_image = 'http://'.$_SERVER['HTTP_HOST'].Videos::PATH_IMAGE.$Videos->image_filename;
        } 
		//---------------------------------------------------------------------------------------------------------------------        
		$tags = CHtml::listData(VideosHasTegs::model()->withVideos($Videos->id)->findAll(), 'id', 'teg_id'); 
		$tags = Tags::model()->with('transfer:nameNoEmpty')->orderByIdDesc()->published()->findAllByPk($tags);//->orderByOrderNum()
        //-----------------------------------------------------------------------------------------------------------------------   
		$otherItems = $Videos->getOther(); 
		//----------------------------------------------------------------------------------------------------------------------- 
		$this->render('view', array('Videos' 	=> $Videos, 
									'tags'    	=> $tags,
									'VideoCats' => $VideoCats,
									'otherItems'=> $otherItems ));
	}

}