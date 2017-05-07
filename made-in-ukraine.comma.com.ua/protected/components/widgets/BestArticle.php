<?php
class BestArticle extends CWidget {

	public $articleId; //для того что б исключить статью на какой мы

	public function run(){

		$criteria               = new CDbCriteria;  
        $criteria->select       = 't.*';
        $criteria->condition    = 't.active = 1 AND t.datetime <= NOW() ';  

		if($articleId > 0){
        	$criteria->addCondition("t.id != :id");
        	$criteria->params += array(":id" => $articleId);
        }

        $criteria->limit 	= 4;
		$criteria->offset 	= 0;

		$criteria->order 	= 't.views_num DESC, t.comments_num DESC, t.datetime DESC, t.order_num';
		$articleItems 		= Article::model()->with('transfer:nameNoEmpty')->findAll($criteria);

		$this->render('bestArticle', array('articleItems' => $articleItems));
	}
} 