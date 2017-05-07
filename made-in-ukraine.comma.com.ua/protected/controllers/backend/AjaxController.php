<?php

class AjaxController extends CController {

    public $_itemList;
    
      

    public function actionGetAlias(){

        $result = array('susses'    => 1,
                        'alias'     => Base::rus2translit($_POST['name']));
   

        header('Content-type: application/json'); 
        echo json_encode($result); 
    }

    public function actionCheckAlias(){

        $result = array('susses' => 0);
   
        $this_alias = $_POST['this_alias'];
        $model      = $_POST['model'];

        $item       = $model::model()->find('code_name = :this_alias',array(':this_alias' => $this_alias));

        if($item){

            $result = array('susses'  => 0,
                            'id'      => $item->id);
        }
        else{
            $result = array('susses' => 1);
        }

        header('Content-type: application/json'); 
        echo json_encode($result); 
    }


    public function actionUploadify($className){

        $array          = array('success' => 0);
        $timestamp      = Yii::app()->request->getParam('timestamp', 0);
        $token          = Yii::app()->request->getParam('token', 0); 
        $data_id        = Yii::app()->request->getParam('data_id', 0); 
        $verifyToken    = md5('unique_salt' . $timestamp); 
 
        if (!empty($_FILES) && $token == $verifyToken){

            $file = $_FILES['Filedata'];

            // так как эта библиотека чего то ничего о типе не возвращает 
            $type = Base::getExtension5($file['name']);  

            $_Photos = Photos::model()->find(array('condition'  => 'gallery_id = :gallery_id',
                                                   'params'     => array(':gallery_id' => $data_id),
                                                   'order'      => 'order_num DESC'));

            if(!$_Photos){
                $order_num = 1;
            } else {
                $order_num = ++$_Photos->order_num;
            }

            $Photos              = new Photos(); 
            $Photos->gallery_id  = $data_id;
            $Photos->size        = $file['size'];
            $Photos->type        = $type;
            $Photos->active      = 1;   
            $Photos->order_num   = $order_num;   

            if($Photos->save()){ 

                $html   = '<tr>
                            <td>'.$Photos->id.'</td>

                            <td>'.CHtml::image(Photos::PATH_IMAGE.$Photos->image_filename).'</td>
 
                            <td> 
                                <input name="'.$className.'[photosRow]['.$Photos->id.'][order_num]" maxlength="5" size="3" type="text" value="'.$Photos->order_num.'" style=" width: 45px;">
                            </td>
                            <td>
                                <input name="'.$className.'[photosRow]['.$Photos->id.'][active]" size="1" type="checkbox" value="1" checked="" />
                            </td>
                            <td><span type="'.$className.'" id-file="'.$Photos->id.'" id-model="'.$sales_id.'" class="del-photo minia-icon-close"></span> </td>
                        </tr>'; 

                $array  = array('success' => 1, 'html' => $html); 

            } // end photo save 
        }  
 
        header('Content-type: application/json'); 
        echo json_encode($array);
    }
 
    
    public function actionDelPhoto(){

        $array      = array('success' => 0);
        $type       = Yii::app()->request->getParam('type', 0); 
        $id_photo   = Yii::app()->request->getParam('id_photo', 0); 
        $id_model   = Yii::app()->request->getParam('id_model', 0); 

        $Photos = Photos::model()->findByPk($id_photo); 
        if($Photos){
            if($Photos->delete()){  

                $array  = array('success' => 1);
            }
        }   

        header('Content-type: application/json'); 
        echo json_encode($array);
    }


    public function actionUploadifyDocs($className){

        $array        = array('success' => 0);
        $timestamp    = Yii::app()->request->getParam('timestamp', 0);
        $token        = Yii::app()->request->getParam('token', 0); 
        $verifyToken  = md5('unique_salt' . $timestamp); 
 
        $data_id      = Yii::app()->request->getParam('data_id', 0);
  
  
        if(!empty($_FILES) && $token == $verifyToken && $data_id > 0){ 

            $file = $_FILES['Filedata'];

            // так как эта библиотека чего то ничего о типе не возвращает 
            $type = Base::getExtension5($file['name']);   
            //поиск последнего порядкового номера, после чего делаем ++, если нет то - 1
            
            $orderNum   = 1; 
 
            //из за того что поля в подтаблице по разному назвал изначально то, приходиться извратиться
            switch ($className) {                
                case 'News':
                    $class   = new NewsHasFiles(); 
                    $objects = $class->findAll(array( 'select'    => 'id, file_id',
                                                      'condition' => 'news_id = :news_id',
                                                      'params'    => array(':news_id' => $data_id)));
                    break;
                case 'Section':
                    $class   = new SectionHasFiles(); 
                    $objects = $class->findAll(array( 'select'    => 'id, file_id',
                                                      'condition' => 'section_id = :section_id',
                                                      'params'    => array(':section_id' => $data_id)));
                    break; 
            }

            if(count($objects) > 0){
                $objectsArrray = CHtml::listData($objects, 'id', 'file_id');

                $Files = Files::model()->findByPk($objectsArrray, array('order'  => 'order_num DESC'));
            }   
            
            if($Files){
                $orderNum = ++$Files->order_num;
            }     



            $Files              = new Files(); 
            $Files->size        = $file['size'];
            $Files->type        = $type;
            $Files->active      = 1;   
            $Files->order_num   = $orderNum; 

            if($Files->save()){

                $FilesTransfer              = new FilesTransfer();
                $FilesTransfer->parent_id   = $Files->id;
                $FilesTransfer->language_id = 1;
                $FilesTransfer->name        = $file['name'];
                $FilesTransfer->save(); 

                $FilesTransfer              = new FilesTransfer();
                $FilesTransfer->parent_id   = $Files->id;
                $FilesTransfer->language_id = 2;
                $FilesTransfer->name        = $file['name'];
                $FilesTransfer->save(); 

                
               
                if($className == 'News'){   

                    // добавим связь
                    $NewsHasFiles           = new NewsHasFiles();
                    $NewsHasFiles->news_id  = $news_id;
                    $NewsHasFiles->file_id  = $Files->id;
                    $NewsHasFiles->save(); 
                     

                    $html   = '<tr>
                                <td>'.$Files->id.'</td>

                                <td><a target="_blank" href="'.Files::PATH_FILE.$Files->file_name.'"> Файл </a></td>

                                <td>
                                    <input name="'.$className.'[filesRow]['.$Files->id.'][title]" type="text" value="'.$Files->transfer->title.'">
                                </td> 

                                <td>'.$tagsSelect.'</td>   

                                <td> 
                                    <input name="'.$className.'[filesRow]['.$Files->id.'][order_num]" maxlength="5" size="3" type="text" value="'.$Files->order_num.'" style=" width: 45px;">
                                </td>
                                <td>
                                    <input name="'.$className.'[filesRow]['.$Files->id.'][active]" size="1" type="checkbox" value="1" checked="" />
                                </td>
                                <td><span type="'.$className.'" id-file="'.$Files->id.'" id-model="'.$news_id.'" class="del-file minia-icon-close"></span></td>
                            </tr>';  
                    
                    $array  = array('success' => 1, 'html' => $html);

                }  else if($className == 'Section'){   

                    // добавим связь
                    $SectionHasFiles              = new SectionHasFiles();
                    $SectionHasFiles->section_id  = $data_id;
                    $SectionHasFiles->file_id     = $Files->id;
                    $SectionHasFiles->save(); 
                     

                    $html   = '<tr>
                                <td>'.$Files->id.'</td>

                                <td><a target="_blank" href="'.Files::PATH_FILE.$Files->file_name.'"> Файл </a></td>

                                <td>
                                    <input name="'.$className.'[filesRow]['.$Files->id.'][name][1]" type="text" value="'.$Files->transfer->name.'">
                                </td>  
                                <td>
                                    <input name="'.$className.'[filesRow]['.$Files->id.'][name][2]" type="text" value="'.$Files->transfer->name.'">
                                </td>  

                                <td> 
                                    <input name="'.$className.'[filesRow]['.$Files->id.'][order_num]" maxlength="5" size="3" type="text" value="'.$Files->order_num.'" style=" width: 45px;">
                                </td>
                                <td>
                                    <input name="'.$className.'[filesRow]['.$Files->id.'][active]" size="1" type="checkbox" value="1" checked="" />
                                </td>
                                <td><span type="'.$className.'" id-file="'.$Files->id.'" id-model="'.$data_id.'" class="del-file minia-icon-close"></span></td>
                            </tr>';  
                    
                    $array  = array('success' => 1, 'html' => $html);

                }  

            }//end if file save
        }
 
        header('Content-type: application/json'); 
        echo json_encode($array);
    }

    public function actionDelFile(){

        $array      = array('success' => 0);
        $type       = Yii::app()->request->getParam('type', 0); 
        $id_model   = Yii::app()->request->getParam('id_model', 0); 
        $id_file    = Yii::app()->request->getParam('id_file', 0); 

        /* по сути нао только ID файла, но на всякий случай для дальнейших действий я все передаю,
            так как сейчас файл заливаем в какой то из модулей, и из другого модуля нельзя выбрать файл.
            но если передумают то сразу будет возможность, удалять файл из модуля только (как бы обрывать связь)
        
        switch ($type) {
            case 'salesToolkit':
                

                break; 
        }
        */
       
        $Files = Files::model()->findByPk($id_file); 
        if($Files){
            if($Files->delete()){
                $array  = array('success' => 1);
            }
        }   

        header('Content-type: application/json'); 
        echo json_encode($array);
    }
    

    

    public function actionChangeElements(){

        $array      = array('success' => 0);
        $model      = Yii::app()->getRequest()->getPost('model'); 
        $action     = Yii::app()->getRequest()->getPost('action'); 
        $idsArray   = Yii::app()->getRequest()->getPost('idsArray'); 
        $model      = ucfirst($model);

        $modelItems = CActiveRecord::model($model)->findAllByPk($idsArray);
        if($modelItems){ 
            foreach($modelItems as $Model){


                if(isset($Model->update)){
                    $Model->update = true;
                }

                switch ($action) {
                    case 'active_choose': 

                        $Model->active = 1;
                        $Model->update(array('active')); 
                        break; 
                    case 'de-active_choose': 
                        
                        $Model->active = 0;
                        $Model->update(array('active')); 
                        break; 
                    case 'delete_choose':
                        $Model->delete();
                        break; 
                }

            } // end foreach


            if($model == 'Comment'){
                $Comment = Comment::recountAllComments();
            }

            $array = array('success' => 1);
        } 

        header('Content-type: application/json'); 
        echo json_encode($array);
    }

 

    public function actionChandeOrderNum(){

        $array      = array('susses' => 0);
        $itemsId    = Yii::app()->getRequest()->getPost('itemsId');
        $itemsOrder = Yii::app()->getRequest()->getPost('itemsOrder');
        $class      = ucfirst(Yii::app()->getRequest()->getPost('model')); 
        

        if(count($itemsId) > 0 && count($itemsOrder) > 0 && $class != NULL){

            foreach ($itemsId as $key => $id) {
                
                $class  = new $class;  
                $Model  = $class->findByPk($id); 

                if($Model){
                    $Model->order_num  = (int)$itemsOrder[$key];
                    $Model->update(array('order_num')); 
                } 
            } 
            
            $array  = array('susses' => 1);
        }   

        header('Content-type: application/json'); 
        echo json_encode($array);  
    }  

 

    public function actionLoadPhoto($type,$data_id=0){

        //$start = microtime(true);

        $response = array('error' => 1, 'errorMsg' => 'No model');
        if ($type !== null){

            $Model = CActiveRecord::model($type)->findByPk($data_id);
            //$Model->start = $start;
            //$time = microtime(true) - $start;
           // printf('%.4F init class end .', $time);
           //echo '<br>';

            if ($Model !== null){ 

                if ($Model->Save() && count($Model->getErrors()) == 0)
                    $response = array(
                        'error'=>0,
                        'file'=>$Model->getUploadedFile()
                    );
                else
                    $response = array(
                        'error' => 1,
                        'errorMsg' => $Model->getErrors()
                    );
            }
        }

       //$time = microtime(true) - $start;
        //printf('%.4F init class end .', $time);
        //exit;
        
        header('Content-type: application/json');
        echo json_encode($response);
    }

    
 


    public function actionDelTag(){

        $array      = array('success' => 0);
        $data_id    = Yii::app()->getRequest()->getPost('data_id');  
        $type_data  = Yii::app()->getRequest()->getPost('type_data');  

        switch ($type_data) {
            case 'article-tag':
                ArticleHasTegs::model()->deleteByPk($data_id); 
                $array = array('success' => 1);
                break; 
            case 'video-tag':
                VideosHasTegs::model()->deleteByPk($data_id); 
                $array = array('success' => 1);
                break; 
            case'news-tag':
                NewsHasTegs::model()->deleteByPk($data_id); 
                $array = array('success' => 1);
                break; 
            case'gallery-tag':
                GalleryHasTegs::model()->deleteByPk($data_id); 
                $array = array('success' => 1);
                break; 
        } 
            
        header('Content-type: application/json'); 
        echo json_encode($array);
    } 


    public function actionGetTagsRow(){ 
        $this->renderPartial('/tags/_row');
    } 

    public function actionSetTagsAutocomplete(){

        //$tags = Tag::model()->published()->ordered()->withWord($_GET['term'])->findAll();
        $tag_name = Yii::app()->request->getParam('term');
        if($tag_name == ''){
            $tag_name = Yii::app()->request->getParam('query');
        }
        
        if($tag_name != ''){
            

            $className = 'Tags'; 
            $searchCriteria             = new CDbCriteria;
            $searchCriteria->select     = 'id, parent_id';
            $searchCriteria->distinct   = true;
            $searchCriteria->addSearchCondition('name', $tag_name, true); 
            $searchCriteria->limit = 10;

            $transferClassName  = $className.'Transfer';
            $searchResult       = $transferClassName::model()->findAll($searchCriteria);    

            $ids = CHtml::listData($searchResult, 'id', 'parent_id'); 
            
            $criteria                = new CDbCriteria; 
            $searchCriteria->select  = 't.*'; 
            if(count($ids) > 0){
                $criteria->addInCondition('t.id', $ids);
            } else {
                $criteria->condition = 't.id = -1'; // для того что б ничего не нашло
            }

            $tagsItems = Tags::model()->published()->findAll($criteria);
        }
  

        $response = array();
        if($tagsItems){
           foreach ($tagsItems as $Tags){

                $rusTransfer  = $transferClassName::model()->find(array('condition' => 'parent_id = :parent_id 
                                                                                        AND language_id = :language_id',
                                                                        'params'    => array(':parent_id' => $Tags->id,
                                                                                            ':language_id' => 1))); 

                $ukrTransfer  = $transferClassName::model()->find(array('condition' => 'parent_id = :parent_id 
                                                                                        AND language_id = :language_id',
                                                                        'params'    => array(':parent_id' => $Tags->id,
                                                                                            ':language_id' => 2)));  
 

                $response[] = array(
                    'id'            => $Tags->id,
                    'label'         => $rusTransfer->name,
                    'suggestions'   => $rusTransfer->name,
                    'value'         => $rusTransfer->name,
                    
                    'ru_name'       => $rusTransfer->name,
                    'ua_name'       => $ukrTransfer->name, 
                    
                );
            
            }
        }  
        
        header('Content-type: application/json');
        echo json_encode($response);
    }

    /*
     public function actionautocomplete(){

        $array = array('success' => 0); 



        header('Content-type: application/json'); 
        echo json_encode($array);
    }
      */
} // end class
 
 