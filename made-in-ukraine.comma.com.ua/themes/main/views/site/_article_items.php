<?php  
    foreach ($items as $Article) {
        $this->renderPartial('/site/_article_item', array('Article' => $Article, 'url' => $this->url));
    }
?>
