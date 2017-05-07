<?php

class EditorsController extends BackEndController {
 

	public function init(){  

		parent::init(); 
 		
 		$this->activeModule = 'editors';
		$this->moduleName 	= 'Пользователи';  

		if($this->role == 'editor'){  
 		 
			$this->redirect('index');	
			exit; 
   		} 

		return true;
	}

	public function actionCreate(){
 

    	$className  = ucfirst('editors');
    	$model 		= new $className;   
		 
 		$post = Yii::app()->request->getPost($className);
		if($post){

			$model->attributes=$_POST[$className]; 

			if($model->save()) {  
				
				$params = '';
				if($_SERVER['QUERY_STRING'] != '')
					$params = '/?'.$_SERVER['QUERY_STRING'];

				$this->redirect(array('support/'.$model_name.$params));
			}
		} 

		$this->render('create',array('model'			=> $model,
									 'model_name'		=> $model_name,
									 'model_transfer'	=> $model_transfer,
								));

	}


	public function actionUpdate($id) { 
	 	
	 	//если не админ то $id - присваивается тот какой сейчас залогинен
	 	//if($this->role != 'admin'){
	 	//	$id = Yii::app()->user->id;
	 	//}

		$model_name 		= 'editors';
		$this->activeModule = $model_name;

		$className  		= ucfirst($model_name); 
		$model 				= $this->loadModel($className,$id);  
 
    	 
		$post = Yii::app()->request->getPost($className);
		if(isset($post)){

			$model->attributes = $post;   

			if($model->save()) {   

				$params = '';
				if($_SERVER['QUERY_STRING'] != '')
					$params = '/?'.$_SERVER['QUERY_STRING'];

				$this->redirect(array('support/'.$model_name.$params));
			} 	
		}
 

		$this->render('/support/update',array(
									'model'				=> $model,
									'model_name'		=> $model_name 
								));
	}


	public function actionDelete($id){
 		
 		$model_name = 'editors';
		$className  = ucfirst($model_name); 

		if($this->role == 'superAdmin'){
 			$this->loadModel($className,$id)->delete();
		}  

		$params = '';
		if($_SERVER['QUERY_STRING'] != '')
			$params = '/?'.$_SERVER['QUERY_STRING'];

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('support/'.$model_name.$params));
	  
	}

	 
	 
}
