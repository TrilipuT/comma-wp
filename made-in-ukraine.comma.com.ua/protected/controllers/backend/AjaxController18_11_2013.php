<?php

class AjaxController extends CController {

    public $_itemList;
    
    public function actionGetMails(){

        $result = array('success' => 0);

        $mailerGroups  = Yii::app()->request->getPost('mailerGroups');
        $mailsArray    = array();

        if(count($mailerGroups) > 0 ){ 
            foreach($mailerGroups as $groupId){

                $MailerGroup = MailerGroup::model()->published()->findByPk($groupId);
                if($MailerGroup){

                    $editorsItems = Editors::model()->published()->findAll('mail_group = :mail_group', array(':mail_group' => $MailerGroup->id));

                    if($editorsItems){
                        foreach ($editorsItems as $key => $Editors) {
                            
                            $mailsArray[$Editors->email] = $Editors->email;

                        }// end foreach
                    }
                }

            } // end foreach
        } 


        if(count($mailsArray) > 0){

            $result = array('success' => 1, 'mailsArray' => $mailsArray);    
        }


        header('Content-type: application/json'); 
        echo json_encode($result); 
    }



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

            $Photos              = new Photos(); 
            $Photos->gallery_id  = $data_id;
            $Photos->size        = $file['size'];
            $Photos->type        = $type;
            $Photos->active      = 1;   

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
                            <td><span type="'.$className.'" id-file="'.$Photos->id.'" id-model="'.$sales_id.'" class="del-file minia-icon-close"></span> </td>
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



    public function actionGetWorker(){

        $array      = array('success' => 0); 
        $data_id    = Yii::app()->request->getParam('data_id', 0); 
        $id_model   = Yii::app()->request->getParam('id_model', 0); 

        $CategoryWeNeedTo = CategoryWeNeedTo::model()->findByPk($data_id);
        if($CategoryWeNeedTo){
            
            $ProjectHasWeNeedTo                     = new ProjectHasWeNeedTo(); 
            $ProjectHasWeNeedTo->we_need_to_cat_id  = $CategoryWeNeedTo->id;
            $ProjectHasWeNeedTo->project_id         = $id_model;

            if($ProjectHasWeNeedTo->save()){

                $html = $this->renderPartial('/layouts/we_need_item', array( 'CategoryWeNeedTo' => $CategoryWeNeedTo, 
                                                                             'project_id'       => $id_model,
                                                                             'we_need_to_id'    => $ProjectHasWeNeedTo->id ), true);
            
                $array = array('success' => 1, 'html' => $html); 
            }   
        }


        header('Content-type: application/json'); 
        echo json_encode($array);
    }
 
    public function actionAddAbility(){

        $array        = array('success' => 0); 
        $id_cat       = Yii::app()->request->getParam('id_cat', 0); 
        $id_model     = Yii::app()->request->getParam('id_model', 0); 
        $ability_id   = Yii::app()->request->getParam('ability_id', 0); 
        $we_need_to_id= Yii::app()->request->getParam('we_need_to_id', 0);


        $WeNeedToHasAbility                 = new WeNeedToHasAbility();
        $WeNeedToHasAbility->we_need_to_id  = $we_need_to_id;
        $WeNeedToHasAbility->ability_id     = $ability_id;
        $WeNeedToHasAbility->project_id     = $id_model;
        $WeNeedToHasAbility->cat_id         = $id_cat;

        if($WeNeedToHasAbility->save()){
            
            $array = array('success' => 1, 'data_id' => $WeNeedToHasAbility->id); 
        }

        header('Content-type: application/json'); 
        echo json_encode($array);
    }  
  
    public function actionDeleteProjectWeNeed(){
        
        $array        = array('success' => 0); 
        $we_need_to_id= Yii::app()->request->getParam('we_need_to_id', 0);

        $ProjectHasWeNeedTo = ProjectHasWeNeedTo::model()->findByPk($we_need_to_id);
        if($ProjectHasWeNeedTo){
            $ProjectHasWeNeedTo->delete();
            $array = array('success' => 1); 
        }
        
        header('Content-type: application/json'); 
        echo json_encode($array);
    }

    public function actionAbilityItemDelete(){
        
        $array      = array('success' => 0); 
        $data_id    = Yii::app()->request->getParam('data_id', 0);

        $WeNeedToHasAbility = WeNeedToHasAbility::model()->findByPk($data_id);
        if($WeNeedToHasAbility){
            $WeNeedToHasAbility->delete();
            $array = array('success' => 1); 
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


 /*
    private function getMailTemplate(){

        $news       = Yii::app()->getRequest()->getPost('news');
        $newsItems  = array();

        if(count($news) > 0){

            $criteria = new CDbCriteria;
            $criteria->addInCondition('id', $news);  
            $newsItems = News::model()->published()->findAll($criteria);
        } 

        $text       = Yii::app()->getRequest()->getPost('description');
         
        return $this->renderPartial('_template1', array('post' => $_POST, 'newsItems' => $newsItems, 'text' => $text),true); 

    }


    public function actionPrevSendMail(){
        
        $array          = array();
 
         
        $array['html']  = $this->getMailTemplate(); 

        header('Content-type: application/json'); 
        echo json_encode($array);
    }

    

    public function actionSendMail(){

        
        $array  = array('susses' => 0, 'errArr' => '');

        $mailerArray    = array(); 
        $mailerEnters   = Yii::app()->getRequest()->getPost('mailerEnters');
        if($mailerEnters != ''){

            $mailerEnters = explode("\n", $mailerEnters); 

            if(count($mailerEnters) > 0){
                foreach($mailerEnters as $email){

                    $mailerArray[$email] = $email;
                }
            } 
        }

        $final_list = Yii::app()->getRequest()->getPost('final_list');
        if(count($final_list) > 0 ){
            foreach($final_list as $email){ 
                $mailerArray[$email] = $email;
            }
        }

        $subject = Yii::app()->getRequest()->getPost('subject');
        if($subject == ''){
            $subject = 'iplace mail';
        }

        $body = $this->getMailTemplate(); 

        if(count($mailerArray) > 0){
            foreach($mailerArray as $email){  

                    if($email == '') continue;
                    if(!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $email)) continue;


                    $mailer = Yii::createComponent('application.extensions.mailer.EMailer');

                    try {
                        $mailer->Host = 'localhost';
                        //$mailer->IsSMTP();
                        $mailer->From = 'robot@robot.com'; 
                        $mailer->AddReplyTo(Settings::getItemByKey('mail-reply-to'), 'reply');   
                        $mailer->ClearAddresses();
                        $mailer->ClearAttachments();
                        $mailer->AddAddress($email);
                        $mailer->IsHTML(true); 
                        $mailer->CharSet    = 'UTF-8';
                        $mailer->FromName   = 'robot';
                        $mailer->Subject    = $subject;  
     
                        $mailer->Body       = $body; 

                        $mailer->Send();
                    }catch (phpmailerException $e){
                        $array['errArr'] .= ' '.$e->errorMessage();
                    }  
                    catch (Exception $e){

                        var_dump($e);
                        $array['errArr'] .= ' '.$e;
                    }  

                //sleep(10);  
            }// end foreach  
        } // end if 

        if($array['errArr'] == '')
            $array['susses'] = 1;

        header('Content-type: application/json'); 
        echo json_encode($array); 

    }// end method actionSendMail

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


    public function actionGetGoogleAnalytics(){

        $array                  = array();
        $googleAnalyticsItems   = GoogleAnalytics::model()->findAll("date_format(date, '%Y%m') = date_format(now(), '%Y%m')");

        if(count($googleAnalyticsItems) > 0){

            $list   = CHtml::listData($googleAnalyticsItems, 'date', 'visits');  
            foreach ($list as $date => $visits) {
                
                $array[] = array(date('j',strtotime($date)), $visits);
            } 
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

                if ($Model->Save())
                    $response = array(
                        'error'=>0,
                        'file'=>$Model->getUploadedFile()
                    );
                else
                    $response = array(
                        'error' => 1,
                        'errorList' => $Model->getErrors()
                    );
            }
        }

       //$time = microtime(true) - $start;
        //printf('%.4F init class end .', $time);
        //exit;
        
        header('Content-type: application/json');
        echo json_encode($response);
    }

    


    public function actionAspell($word){

        $word = iconv("utf-8", "koi8-r", $word);
        $pspell_link = pspell_new ("ru");
        if (!pspell_check ($pspell_link, $word)) {
            echo "Ошибка в слове<br />Возможные значения:";
            $sugg = pspell_suggest($pspell_link, $word);
            foreach ($sugg as $sug){
            echo  iconv("koi8-r", "utf-8", $sug)."<br />";
            }
        } else {
            echo "Слово верно";
        }
 
    }


    public function actiondelTag(){

        $array      = array('success' => 1);
        $data_id    = Yii::app()->getRequest()->getPost('data_id'); 
        $tag_id     = Yii::app()->getRequest()->getPost('tag_id'); 
        $type_data  = Yii::app()->getRequest()->getPost('type_data');   

        switch ($type_data) {
            case 'news-tag':
                NewsHasTags::model()->deleteAll(array('condition'   => 'news_id = :news_id AND tag_id = :tag_id',
                                                      'params'      => array(':news_id' => $data_id, ':tag_id' => $tag_id))); 
                break;
            case 'pressrelease-tag':
                PressreleaseHasTags::model()->deleteAll(array('condition'   => 'pressrelease_id = :pressrelease_id AND tag_id = :tag_id',
                                                              'params'      => array(':pressrelease_id' => $data_id, ':tag_id' => $tag_id))); 
                break;  
            case 'event-tag':
                EventHasTags::model()->deleteAll(array('condition'   => 'event_id = :event_id AND tag_id = :tag_id',
                                                       'params'      => array(':event_id' => $data_id, ':tag_id' => $tag_id))); 
                break;  

            case 'expert-tag':
                ExpertHasTags::model()->deleteAll(array('condition'   => 'expert_id = :expert_id AND tag_id = :tag_id',
                                                       'params'      => array(':expert_id' => $data_id, ':tag_id' => $tag_id))); 
                break;     

            case 'video-tag':
                VideoHasTags::model()->deleteAll(array('condition'   => 'video_id = :video_id AND tag_id = :tag_id',
                                                       'params'      => array(':video_id' => $data_id, ':tag_id' => $tag_id))); 
                break;     
        } 
            
        header('Content-type: application/json'); 
        echo json_encode($array);
    }
    
    public function actionautocomplete(){

        $array = array('success' => 0); 



        header('Content-type: application/json'); 
        echo json_encode($array);
    }


    public function actionGetTagsRow(){ 
        $this->renderPartial('/tags/_row');
    } 

    public function actionSetTagsAutocomplete(){

        //$tags = Tag::model()->published()->ordered()->withWord($_GET['term'])->findAll();
        $tag_name = Yii::app()->request->getParam('term');
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
                    'id'      => $Tags->id,
                    'label'   => $rusTransfer->name,
                    'value'   => $rusTransfer->name,
                    
                    'ru_name' => $rusTransfer->name,
                    'ua_name' => $ukrTransfer->name
                );
            
            }
        }  
        
        header('Content-type: application/json');
        echo json_encode($response);
    }
      */
} // end class
 
 