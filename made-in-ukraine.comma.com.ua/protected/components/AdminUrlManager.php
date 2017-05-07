<?php
class AdminUrlManager extends CUrlManager {
    
    private function accessRules(){

        return array(
            array('superAdmin',
                'actions'=>array('index'), 
            ),
            array('admin',
                'actions'=>array('index','logout','update','create','delete' ),               
            ),
            array('editor',
                'actions'=>array('login','logout'), 
            ), 
        );
    }

   
    public function parseUrl($request){
     
        $app = Yii::app();  
        
        // Сначала парсим общие правила, для перегрузки обязательных ссылок
        // таких, как админка
        $route = parent::parseUrl($request); 
        
        // Проверяем, подошли ли общие правила и есть ли контроллер для них
        $ca = $app->createController($route); 
        
        if(Yii::app()->user->id) 
            $role = Editors::model()->getUserRole(Yii::app()->user->id);  

        if($role == 'superAdmin'){

            $session = new CHttpSession;
            $session->open();
             
            if($session['action'] != NULL){
                $this->setAction($session['action']);
            } 

            //  echo '<pre>'; var_dump($session['action']); echo '</pre>'; //actions getAction  setAction
            //  exit;
        }
 


        if($ca[1] != NULL){
            //$ca[0]->setAction($ca[1]);
        }

        $session = new CHttpSession;
        $session->open();
        $session['action'] = $ca[1]; 

        //echo '<pre>'; var_dump($ca[1], $route); echo '</pre>'; //actions getAction  setAction
        //exit; 

        return $route;
    } 

} 