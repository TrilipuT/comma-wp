 <div class="container_for_grid margin_bottom_20 clearfix">
    <div class="grid_3of4">

        <div class="articles articles_wide">
 
            <?php $this->renderPartial('_items', array('result' => $result, 'url' => $this->url, 'q'=>$q)); ?> 
        </div>

        
        <!--
        <div class="pagination">
            <div class="pagination-pages">
                <a href="#" class="prev"></a>
                <a href="#" class="next"></a>
                <a href="#">1</a>
                <span>2</span>
                <a href="#">3</a>
                <a href="#">4</a>
                <a href="#">5</a>
                <span>...</span>
                <a href="#">161</a>
            </div>
        </div>-->

    </div>
    <div class="grid_1of4 grid_last">
        <div class="scroll_switcher">
            <div>
                <div class="scroll_switcher-item">
                    <?php $this->widget('application.components.widgets.Banner'); ?> 
                </div>              
                
                <?php $this->widget('application.components.widgets.SocialsBlock'); ?> 
            </div>
        </div>
    </div>
</div> 



<?php /*<ul>
                            <li><a href="/search.html<?=($q ? '?q='.$q : '')?>" <?=($category == '' ? 'class="is-active"' : ($all_num == 0 ? 'class="is-inactive"' : ''))?>><?=Yii::t('app','search_category_all')?> (<?=$all_num?>)</a></li>
                            <li><a href="/search/news.html<?=($q ? '?q='.$q : '')?>" <?=($category == 'news' ? 'class="is-active"' : ($news_num == 0 ? 'class="is-inactive"' : ''))?>><?=Yii::t('app','search_category_news')?> (<?=$news_num?>)</a></li>
                            <li><a href="/search/videos.html<?=($q ? '?q='.$q : '')?>" <?=($category == 'videos' ? 'class="is-active"' : ($videos_num == 0 ? 'class="is-inactive"' : ''))?>><?=Yii::t('app','search_category_videos')?> (<?=$videos_num?>)</a></li>
                            <li><a href="/search/pressreleases.html<?=($q ? '?q='.$q : '')?>" <?=($category == 'pressreleases' ? 'class="is-active"' : ($pressreleases_num == 0 ? 'class="is-inactive"' : ''))?>><?=Yii::t('app','search_category_pressreleases')?> (<?=$pressreleases_num?>)</a></li>
                            <!--<li><a class="is-inactive" href="#">канал (0)</a></li>-->
                        </ul> */?>