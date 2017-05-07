<?php
class AdminPagination extends CWidget {

	public $page,
	       $total_pages,
	       $module_name,
	       $params;

	public function run() { 

		if($this->params != null){ 
			
			$this->params = preg_replace('|(\/)?\/\?(page=\w)?|', '', $this->params);

			if($this->params[0] != '&')
				$this->params = '&'.$this->params;			
		}

        $this->render('adminPagination', array('params' => $params));
    }
} 