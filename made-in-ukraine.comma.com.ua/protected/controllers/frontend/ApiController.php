<?php


class ApiController extends CController {
    // Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers
     */
    Const APPLICATION_ID = 'ASCCPE';

    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';
    /**
     * @return array action filters
     */
    public function filters(){
        return array();
    }

    /**
     * @return array
     */
    public function behaviors(){
        return array(
           // 'restAPI' => array('class' => '\rest\controller\Behavior')
        );
    }

    private function trueModules(){
        return array("news");
    }

    public function actionList($model, $cat_name = "", $offset = 0, $limit = 20){

        if(!in_array($model, self::trueModules())){
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: is not implemented for model <b>%s</b>',
                $model) );
            Yii::app()->end();
        }

        $_model = CActiveRecord::model(ucfirst($model));

        // Send the response
        $this->_sendResponse(200, CJSON::encode(array()));
    }

    private function _sendResponse($code, $msg){

        var_dump($_GET);
    }
} 