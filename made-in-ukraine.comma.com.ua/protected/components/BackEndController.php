<?php
class BackEndController extends BaseController {

    // лейаут
    public $layout = '/layouts/support';
    
	
	public $languageList = array();


 	public $role; 

    // крошки
    public $breadcrumbs = array();
    
    
    public 	$activeModule,
    		$moduleName; 


    public function init(){  

   		if(Yii::app()->user->id) 
   			$this->role = Editors::model()->getUserRole(Yii::app()->user->id);  

   		$this->languageList = Language::model()->getLanguageList();
 
   		$model_name = Yii::app()->request->getParam('model_name');

   		$modulesArray = array('news' 	 				=> 'Новости', 
							  'section'  				=> 'Разделы', 
							  'editors'  				=> 'Пользователи',
							  'settings' 				=> 'Настройки',
							  'language' 				=> 'Языки'); 

   		if($model_name != NULL){

   			$this->activeModule = $model_name;
   			$this->moduleName 	= $modulesArray[$model_name]; 

   			if(Yii::app()->controller->actionParams['id'] == NUll){
   				$this->breadcrumbs=array(
					$this->moduleName
				);
   			} else {
				$this->breadcrumbs=array(
					$this->moduleName => array('support/'.$model_name)
				);
   			} 
   		}

   		if($this->role == 'editor'){  

   			if($model_name != 'news' && $model_name != 'video' && $model_name != NULL){				 
				$this->redirect('/support/index');	
				exit;
   			}
   			
   		} 
   		
   		return true;	
   }


    public function filters(){ 

		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

    public function accessRules(){

		return array(
			array('allow',
				//'actions'=>array('index','logout','update','create','delete' ),
				'roles'=>array('admin', 'editor', 'superAdmin'),
			),
			array('allow',
				'actions'=>array('login','logout'),
				'roles'=>array('guest'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
 	 
	
	/**
	 * Displays the login page
	 */
	public function actionLogin(){
 
		//если залогинен и пытается сюда зайти реддиректим на главную админки
		if(Yii::app()->user->isGuest==false){
			$this->redirect('index');	
			exit;
		}


		$model = new LoginForm;
		 
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form'){
			
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}


		// collect user input data
		if(isset($_POST['LoginForm'])){ 

			$model->attributes=$_POST['LoginForm'];
 
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()){
				$this->redirect('index');
			}  
				
		}
		

			


		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout(){

		Yii::app()->user->logout();
		$this->redirect('/support/');
	}    

	public function actionError(){
		if($error=Yii::app()->errorHandler->error){
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	public function actionUpload($id=0){
		
		$this->response = array('error'=>1,'errorMsg'=>'No model');
		if ($this->modelName !== null){

			$Model = CActiveRecord::model($this->modelName)->findByPk($id);
			if ($Model !== null){
				
				if ($Model->dbUpload())
					$this->response = array(
						'error'=>0,
						'file'=>$Model->getUploadedFile()
					);
				else
					$this->response = array(
						'error'=>1,
						'errorList'=>$Model->getErrors()
					);
			}
		}
		
		header('Content-type: application/json');
		echo json_encode($this->response);
	}

	public function loadModel($className,$id){

		$model = $className::model()->findByPk($id); 

		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}