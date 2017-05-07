<?php

class Search{
	/*
	public static function searchByParams($q, $tag='', $page = 1, $limit = 5){


        // общее
        $criteria               = new CDbCriteria;  
        $criteria->select       = 't.*';
        $criteria->condition    = 't.active = 1';   
        $criteria->order        = 't.order_num DESC';  
        $criteria->group        = 't.`id`';

        // news ################################################################################### /
        $criteria1          = new CDbCriteria;  
        $criteria1->join    = ' LEFT JOIN `news_transfer` AS news_t ON t.`id` = news_t.`parent_id` AND news_t.language_id = 1 ';

        $criteria1->addSearchCondition('news_t.title', $q);   
            $criteria1->addSearchCondition('news_t.annotation', $q, true, 'OR');     
            $criteria1->addSearchCondition('news_t.text', $q, true, 'OR');   

        if($tag != '' && intval($tag) > 0){

            $criteria1->join        .= ' LEFT JOIN `news_has_tags` AS news_ht ON t.`id` = news_ht.`news_id` ';
            $criteria1->condition   = 'news_ht.tag_id = :tag_id';
            $criteria1->params      = array(':tag_id' => $tag);
        }

        $criteria1->mergeWith($criteria); 

        //  files  ################################################################################### /
        $criteria2          = new CDbCriteria;   
        $criteria2->join    = ' LEFT JOIN `files_transfer` AS files_t ON t.`id` = files_t.`parent_id` AND files_t.language_id = 1 ';      
        
        $criteria2->addSearchCondition('t.file_name', $q);    
            $criteria2->addSearchCondition('files_t.title', $q, true, 'OR');   

        if($tag != '' && intval($tag) > 0){

            $criteria2->join        .= ' LEFT JOIN `files_has_tags` AS files_ht ON t.`id` = files_ht.`file_id` ';
            $criteria2->condition   = 'files_ht.tag_id = :tag_id';
            $criteria2->params      = array(':tag_id' => $tag);
        }

        $criteria2->mergeWith($criteria);  



        $totalNews      = News::model()->count($criteria1);   
        $totalFiles     = Files::model()->count($criteria2);  
        $total          = $totalNews + $totalFiles;  
        $offset         = ( ($page-1) * $limit ); 

        //var_dump($totalNews, $totalFiles, $limit > $total);


        if($limit > $total){ // первая страница и 2я меньше в 2х вариантов в сумме
 
            //выборка с первой таблицы
            $newsItems   = News::model()->findAll($criteria1); 
            $newsCount   = count($newsItems);

            // выборка со второй таблицы
            $filesItems  = Files::model()->findAll($criteria2);   
           
            $allItems    = array_merge($newsItems, $filesItems);  

        } 
        else if($offset < $totalNews && ($offset+$limit) < $totalNews){ //попадаем в диапазон первой выборки полностью

            $criteria1->limit   = $limit;
            $criteria1->offset  = $offset;
            $allItems           = News::model()->findAll($criteria1);

        }
        elseif( $offset > $totalNews ){  //попадаем в диапазон второй выборки полностью

             
            $criteria2->limit   = $limit;
            $criteria2->offset  = $offset-$totalNews;
            $allItems           = Files::model()->findAll($criteria2);

        }
        else{           

            //выборка остатка первой таблицы
            $criteria1->limit   = $limit;
            $criteria1->offset  = $offset;
            $newsItems          = News::model()->findAll($criteria1); 
            $newsCount          = count($newsItems);

            // выборка со второй таблицы
            $criteria2->limit   = $limit-$newsCount;  
            $filesItems         = Files::model()->findAll($criteria2); 

            // var_dump($offset,$totalNews,$totalFiles, $total, '->', count($newsItems ), count($filesItems ), '|', $limit, $newsCount );
   
            $allItems           = array_merge($newsItems, $filesItems); 

        } 

        $total_pages = ceil($total / $limit);
        $remains     = 0;

        if($total_pages > 1){
            $remains = $total - ($page * $limit);
        } 

        $result = array('total'         => $total,
                        'total_pages'   => $total_pages,
                        'page'          => $page, 
                        'remains'       => $remains,
                        'q'             => $q,
                        'items'         => $allItems,
        ); 
          
        return  $result; 
    } */

    public static function getSearchItema($q, $page, $category = ''){

        $result     = array();  
        $onPage     = 10;

        if (trim($q) != '') {
            if ($category == '' || $category == 'news') {
                $news       = News::model()->published()->searchByKeywords($q)->orderByDateDesc()->limit(1000)->findAll();
                $news_num   = count($news);
            } else {
                $news       = array();
                $news_num   = News::model()->published()->searchByKeywords($q)->count();
            }
            //---------------------------------------------
            if ($category == '' || $category == 'videos') {
                $videos     = Videos::model()->published()->searchByKeywords($q)->orderByDateDesc()->limit(1000)->findAll();
                $videos_num = count($videos);
            } else {
                $videos     = array();
                $videos_num = Videos::model()->published()->searchByKeywords($q)->count();
            } 
            //---------------------------------------------
            if ($category == '' || $category == 'photos') {
                $photos     = Gallery::model()->published()->searchByKeywords($q)->orderByDateDesc()->limit(1000)->findAll('in_article = 0');
                $photos_num = count($photos);
            } else {
                $photos     = array();
                $photos_num = Gallery::model()->published()->searchByKeywords($q)->count('in_article = 0');
            } 
            //---------------------------------------------
            if ($category == '' || $category == 'articles') {  
                $articles     = Article::model()->with(array('transfer', 'tags'))->published()->searchByKeywords($q)->orderByDateDesc()->limit(1000)->findAll('blog = 0');
                //$articles     = 
                //Article::search($q);
                $articles_num = count($articles);
            } else {
                $articles     = array();
                $articles_num = Article::model()->published()->searchByKeywords($q)->count('blog = 0');
            } 
            //---------------------------------------------
            if ($category == '' || $category == 'blogs') {  
                $blogs     = Article::model()->published()->searchByKeywords($q)->orderByDateDesc()->limit(1000)->findAll('blog = 1');
                $blogs_num = count($blogs);
            } else {
                $blogs     = array();
                $blogs_num = Article::model()->published()->searchByKeywords($q)->count('blog = 1');
            } 

            //-----------------------------------
            foreach($news as $News){
                $result[] = $News;
            }
            foreach($videos as $Videos){
                $result[] = $Videos;
            }
            foreach($photos as $Gallery){
                $result[] = $Gallery;
            }
            foreach($articles as $Article){
                $result[] = $Article;
            }
            foreach($blogs as $Article){
                $result[] = $Article;
            }
            //-----------------------------------
            
        } else {
            $news       = array();
            $news_num   = 0;
            //---------------
            $videos     = array();
            $videos_num = 0;
            //---------------
            $photos     = array();
            $photos_num = 0;
            //---------------
            $articles       = array();
            $articles_num   = 0;
            //---------------
            $blogs      = array();
            $blogs_num  = 0;
        }  
        //----------------------------------------------------------------
        $resultOffset = ($page-1)*$onPage; 
        $pageResult = array_slice($result, $resultOffset, $onPage);
        $resultLeft = count($result)-$page*$onPage;
        
        $all_num = $news_num + $videos_num + $photos_num + $articles_num + $blogs_num;   
        
        switch ($category) {
            case 'news':
                $total_items = $news_num;
                break;
            case 'videos':
                $total_items = $videos_num;
                break;
            case 'photos':
                $total_items = $photos_num;
                break;
            case 'articles':
                $total_items = $articles_num;
                break;
            case 'blogs':
                $total_items = $blogs_num;
                break; 
            default:
                $total_items = $all_num;
                break;
        } 
        //--------------------------------------------------------------------------------  
        $total_pages        = ceil($total_items / $onPage);
        $remains            = 0;

        if($total_pages > 1){
            $remains = $all_num - ($page * $onPage);
        } 

        $_result = array('total'                => $all_num,
                        'total_pages'           => $total_pages,
                        'page'                  => $page, 
                        'remains'               => $remains,
                        'q'                     => $q,
                        'searchCountNews'       => $news_num,
                        'searchCountArticles'   => $articles_num,
                        'searchCountBlogs'      => $blogs_num,
                        'searchCountPhotos'     => $photos_num,     
                        'searchCountVideos'     => $videos_num, 
                        'pageResult'            => $pageResult,
                        'resultLeft'            => $resultLeft,
                        'result'                => $result
        ); 
          
        return  $_result;    
    }
    
}