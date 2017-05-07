<header style="<?=$color?>"  class="header <?=$class?> <?=$marging?>">
    <div>
        <div class="header-lang">
            <span>Рус</span>
            <a href="/dummy.html">Укр</a>
        </div>
        <div class="socials <?=$this->icon_class?>">
            <a target="_blank" href="https://www.facebook.com/comma.com.ua" class="fb"></a>
            <a target="_blank" href="https://twitter.com/comma_com_ua" class="tw"></a>
            <a target="_blank" href="https://vk.com/commacomua" class="vk"></a>
            <a target="_blank" href="http://instagram.com/commacomua" class="in"></a>
            <a target="_blank" href="/rss/rss.xml" class="rss"></a>
        </div>
        <a href="/" class="header-logo"></a>
        <div class="header-nav">
            <?php if($rubricsItems): 
                $activeLink = '';  
                ?>
                <?php foreach($rubricsItems as $_Rubrics): 
                    
                    $selected  = ''; 
                    $textColor = ''; 
                    if($Rubrics->id == $_Rubrics->id || ($this->activeRubric > 0 && $_Rubrics->id == $this->activeRubric) ){
                        $selected   = 'selected';   
                        $activeLink = $_Rubrics->getItemUrl();
                        if($this->activeRubric > 0 && $_Rubrics->id == $this->activeRubric){
                            $textColor  = 'color:'.$_Rubrics->color.' !important;'; 
                        }
                    }  
                    ?>
                    <a style="<?=$textColor;?>" href="<?=$_Rubrics->getItemUrl()?>" class="<?=$selected;?>">
                        <?=$_Rubrics->transfer->name?>
                        <b></b>
                    </a>
                <?php endforeach;?>
            <?php endif;?>


            <?php if($sectionItems):?>
                <?php foreach($sectionItems as $Section): 

                    $selected = ''; 
                    if($ActiveSection->id == $Section->id){
                        $selected   = 'selected';    
                    } 
                    ?>
                    <a href="<?=$Section->getItemUrl()?>" class="<?=$selected;?>">
                        <?=$Section->transfer->name?>
                        <b></b>
                    </a>

                <?php endforeach;?>
            <?php endif;?>
			<a href="http://made-in-ukraine.comma.com.ua" class="ukraine" target="_blank">Made In Ukraine<b></b></a>
            <span href="#" class="search"><b></b></span> 
        </div>
        
        <!-- SEARCH -->
        <?php if($ActiveSection->controller ==  'C_searchController.php'): ?>

            <div class="header-search always_shown">
                <div class="header-search-title">
                    <?=Constants::getItemByKey('site_search')?>
                </div>
                <form action="<?=$searchUrl;?>"> 
                    <div class="header-search-form">
                        <input autocomplete="off" name="q" type="text" value="<?=urldecode(Yii::app()->request->getQuery('q'));?>" class="inp"/>
                        <button class="btn"><b></b><?=Constants::getItemByKey('search')?></button>
                    </div>
                </form>  
                <div class="header-search-filter">
                    <?php 
                        $q              = trim(urldecode(Yii::app()->request->getQuery('q')));
                        $all_title      = Constants::getItemByKey('all_title');
                        $photos_title   = Constants::getItemByKey('photos_title');
                        $blogs_title    = Constants::getItemByKey('blogs_title');
                        $articles_title = Constants::getItemByKey('articles_title');
                        $news_title     = Constants::getItemByKey('news_title');
                        $video_title    = Constants::getItemByKey('video_title'); 

                        if($activeSubRubricsId == '' && $q != ''):?>
                            <span class="selected"><?=$all_title;?> (<?=$searchCountAll?>)</span>
                        <?php elseif($searchCountAll > 0): ?>
                            <a href="<?=$searchUrl?>?q=<?=$q;?>"><?=$all_title;?> (<?=$searchCountAll?>)<b></b></a>
                        <?php else:?>
                            <span><?=$all_title;?></span>
                        <?php endif;?>


                        <?php if($activeSubRubricsId == 'articles'):?>
                            <span class="selected"><?=$articles_title;?> (<?=$searchCountArticles?>)</span>
                        <?php elseif($searchCountArticles > 0): ?>
                            <a href="<?=$searchUrl?>/articles?q=<?=$q;?>"><?=$articles_title;?> (<?=$searchCountArticles?>)<b></b></a>
                        <?php else:?>
                            <span><?=$articles_title;?></span>
                        <?php endif;?>


                        <?php if($activeSubRubricsId == 'news'):?>
                            <span class="selected"><?=$news_title;?> (<?=$searchCountNews?>)</span>
                        <?php elseif($searchCountNews > 0): ?>
                            <a href="<?=$searchUrl?>/news?q=<?=$q;?>"><?=$news_title;?> (<?=$searchCountNews?>)<b></b></a>
                        <?php else:?>
                            <span><?=$news_title;?></span>
                        <?php endif;?>


                        <?php if($activeSubRubricsId == 'blogs'):?>
                            <span class="selected"><?=$blogs_title;?> (<?=$searchCountBlogs?>)</span>
                        <?php elseif($searchCountBlogs > 0): ?>
                            <a href="<?=$searchUrl?>/blogs?q=<?=$q;?>"><?=$blogs_title;?> (<?=$searchCountBlogs?>)<b></b></a>
                        <?php else:?>
                            <span><?=$blogs_title;?></span>
                        <?php endif;?>


                        <?php if($activeSubRubricsId == 'photos'):?>
                            <span class="selected"><?=$photos_title;?> (<?=$searchCountPhotos?>)</span>
                        <?php elseif($searchCountPhotos > 0): ?>
                            <a href="<?=$searchUrl?>/photos?q=<?=$q;?>"><?=$photos_title;?> (<?=$searchCountPhotos?>)<b></b></a>
                        <?php else:?>
                            <span><?=$photos_title;?></span>
                        <?php endif;?>


                        <?php if($activeSubRubricsId == 'videos'):?>
                            <span class="selected"><?=$video_title;?> (<?=$searchCountVideos?>)</span>
                        <?php elseif($searchCountVideos > 0): ?>
                            <a href="<?=$searchUrl?>/videos?q=<?=$q;?>"><?=$video_title;?> (<?=$searchCountVideos?>)<b></b></a>
                        <?php else:?>
                            <span><?=$video_title;?></span>
                        <?php endif;?> 
                </div>
            </div>

        <?php else:?>

            <form action="<?=$searchUrl;?>"> 
                <div class="header-search">
                    <div class="header-search-form">
                        <input type="text" value="" name="q" class="inp"/>
                        <button class="btn"><b></b><?=Constants::getItemByKey('search')?></button>
                    </div>
                </div> 
            </form> 

        <?php endif;?>
        <!-- END  SEARCH -->



        <?php if($Rubrics):?>

            <div class="header-text">
                <div id="name-parent" style="white-space: nowrap"><span class="header-name"><?=$Rubrics->transfer->name?></span></div>
                <div><?=$Rubrics->transfer->description?></div>
            </div>

            <?php if($subRubricsItems):?>

                <div class="header-subnav">
                    
                    <?php if($activeSubRubricsId):?>
                        <a href="<?=$activeLink?>"><?=Constants::getItemByKey('all_articles')?><b></b></a>
                    <?php else:?>
                        <span><?=Constants::getItemByKey('all_articles')?></span>
                    <?php endif;?> 

                    <?php foreach($subRubricsItems as $SubRubrics): 

                        $selected = ''; 
                        if($SubRubrics->id == $activeSubRubricsId):?>
                            <span><?=$SubRubrics->transfer->name?></span>
                        <?php else: ?>
                            <a href="<?=Rubrics::getSectionUrl()?>/<?=$Rubrics->code_name?>/<?=$SubRubrics->code_name?>">
                                <?=$SubRubrics->transfer->name?>
                                <b></b>
                            </a>
                        <?php endif;?>

                    <?php endforeach;?>
                </div>

            <?php endif;?>

        <?php elseif($ActiveSection->controller == 'C_blogsController.php'):?>   

            <?php if($activeSubRubricsId && $activeSubRubricsId instanceof Blogers):

                $Blogers = $activeSubRubricsId;
                ?>
                <div class="header-blogger clearfix">

                    <?php if(!empty($Blogers->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Blogers::PATH_IMAGE_SMALL.$Blogers->image_filename)): ?>
                        <div class="item-img">
                            <img src="<?=Blogers::PATH_IMAGE_SMALL.$Blogers->image_filename;?>" alt="<?=$Blogers->transfer->name?>"/>
                        </div>
                    <?php endif;?>    
                    <div class="item-name">
                        <?=$Blogers->transfer->name?>
                    </div>
                    <div class="item-about">
                        <?=$Blogers->transfer->description?>
                    </div>
                </div>

            <?php else:?>

                <div class="header-text">
                    <div id="name-parent" style="white-space: nowrap"><span class="header-name"><?=$ActiveSection->transfer->name?></span></div> 
                    <div><?=$ActiveSection->transfer->description?></div>
                </div>

            <?php endif;?>

        <?php elseif($ActiveSection->controller == 'C_tagController.php'):?>  

            <div class="header-text">
                <div id="name-parent" style="white-space: nowrap"><span class="header-name"><?=Yii::app()->request->getQuery('tag');?></span></div>  
            </div>

        <?php elseif($ActiveSection->controller == 'C_newsController.php'):?>  

            <div class="header-text">
                <div id="name-parent" style="white-space: nowrap">
                    <span class="header-name">
                        <?=$ActiveSection->transfer->menu_name?>
                    </span>
                </div>  
            </div>

        <?php elseif($ActiveSection->controller == 'C_videosController.php'):?>  

            <div class="header-text">
                <div id="name-parent" style="white-space: nowrap">
                    <span class="header-name">
                        <?=$ActiveSection->transfer->menu_name?>
                    </span>
                </div>  
            </div>

            <?php if($subRubricsItems):?>

                <div class="header-subnav">
                    
                    <?php if($activeSubRubricsId):?>
                        <a href="<?=VideoCats::getSectionUrl()?>"><?=Constants::getItemByKey('all_videos')?><b></b></a>
                    <?php else:?>
                        <a class="active" href="<?=VideoCats::getSectionUrl()?>"><?=Constants::getItemByKey('all_videos')?></a>
                    <?php endif;?> 

                    <?php foreach($subRubricsItems as $SubRubrics): 

                        $selected = ''; 
                        if($SubRubrics->id == $activeSubRubricsId){
                            $selected = 'active';  
                        }
                        /*if($SubRubrics->id == $activeSubRubricsId):?>
                            <span><?=$SubRubrics->transfer->name?></span>
                        <?php //else: */?>

                            <a class="<?=$selected;?>" href="<?=$SubRubrics->getItemUrl()?>">
                                <?=$SubRubrics->transfer->name?>
                                <?php if($selected != 'active'):?>
                                    <b></b>
                                <?php endif;?>
                            </a>
                        <?php //endif;?>

                    <?php endforeach;?>
                </div>

            <?php endif;?>


        <?php elseif($ActiveSection->controller == 'C_authorsController.php'):?>  

            <?php if($activeSubRubricsId && $activeSubRubricsId instanceof Authors):

                $Authors = $activeSubRubricsId;
                ?>

                <div class="author clearfix">
                    <div>
                        <?php if(!empty($Authors->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Authors::PATH_IMAGE_SMALL.$Authors->image_filename)): ?>
                            <div class="author-img">
                                <img src="<?=Authors::PATH_IMAGE_SMALL.$Authors->image_filename;?>" alt="<?=$Authors->transfer->getName()?>"/>
                            </div>
                        <?php endif;?> 
                        <div class="author-title">
                            <a href="<?=Authors::getSectionUrl()?>"><?=Constants::getItemByKey('header_author')?></a><b></b>
                        </div>
                        <div class="author-name">
                            <?=str_replace(" ", '<br>', $Authors->transfer->getName()) ?>
                        </div>
                        <a href="#" class="author-link"></a>
                    </div>
                </div> 

            <?php else:?>

                <div class="header-text">
                    <div id="name-parent" style="white-space: nowrap">
                        <span class="header-name">
                            <?=$ActiveSection->transfer->menu_name?>
                        </span>
                    </div>  
                </div>
            <?php endif;?>  

        <?php elseif($ActiveSection->controller == 'C_galleryController.php'):?>  
  
                <?php if($activeSubRubricsId && $activeSubRubricsId instanceof Authors):

                $Authors = $activeSubRubricsId;
                ?>

                <div class="author clearfix">
                    <div>
                        <?php if(!empty($Authors->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Authors::PATH_IMAGE_SMALL.$Authors->image_filename)): ?>
                            <div class="author-img">
                                <img src="<?=Authors::PATH_IMAGE_SMALL.$Authors->image_filename;?>" alt="<?=$Authors->transfer->getName()?>"/>
                            </div>
                        <?php endif;?> 
                        <div class="author-title">
                            <a href="<?=Authors::getSectionUrl()?>/photographers/"><?=Constants::getItemByKey('header_photographer')?></a><b></b>
                        </div>
                        <div class="author-name">
                            <?=str_replace(" ", '<br>', $Authors->transfer->getName()) ?>
                        </div>
                        <a href="#" class="author-link"></a>
                    </div>
                </div> 

            <?php else:?>

                <div class="header-text header-text_photos">
                    <div id="name-parent" style="white-space: nowrap">
                        <span class="header-name">
                            <?=$ActiveSection->transfer->menu_name?>
                        </span>
                    </div>  
                </div>
            <?php endif;?> 

        <?php elseif($ActiveSection->transfer->menu_name != "" && $ActiveSection->controller != 'C_aboutController.php' ):?>

            <div class="header-text">
                <div id="name-parent" style="white-space: nowrap">
                    <span class="header-name">
                        <?=$ActiveSection->transfer->menu_name?>
                    </span>
                </div>  
            </div>

        <?php endif;?>   

    </div>
</header> 