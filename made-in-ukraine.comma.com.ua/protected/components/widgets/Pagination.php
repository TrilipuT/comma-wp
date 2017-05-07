<?php
class Pagination extends CWidget {

	public $page,
	       $total_pages,
	       $params,
	       $url,
	       $remains,
	       $remains_class;

	public function run() { 

		if($this->params != null){ 

			if(count($this->params) > 0){
				$params = array_filter($this->params);
			}
			  
			$params = '&'.http_build_query($params);
		}


        $this->render('pagination', array('params'=> $params ));
    }
} 