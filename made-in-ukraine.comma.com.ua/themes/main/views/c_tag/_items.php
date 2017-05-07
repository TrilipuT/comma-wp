<?php foreach($result as $Obj):
        
        $objTitle       = strip_tags($Obj->transfer->name); 
        $objAbsctract   = strip_tags($Obj->transfer->description); 
         
        //---------------------------------------------------------------------------------------------------------------------------- 
        $isVideo      = false;
        $img          = '';
        $background   = '';
        $border       = '';
        $sectionLink  = '';
        $className    = get_class($Obj); //var_dump($className);
        switch($className){

            case 'News':
                if(!empty($Obj->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].News::PATH_IMAGE.$Obj->image_filename)){
                    $img = News::PATH_IMAGE.$Obj->image_filename;
                }

                $typeName       = Constants::getItemByKey('news_title');
                $sectionLink    = News::getSectionUrl();

            break;
            case 'Article':
                if(!empty($Obj->icon_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Article::PATH_ICON_SMALL.$Obj->icon_filename)){
                    $img = Article::PATH_ICON_SMALL.$Obj->icon_filename;
                } else if(!empty($Obj->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Article::PATH_ICON_SMALL.$Obj->image_filename)){
                    $img = Article::PATH_ICON_SMALL.$Obj->image_filename;
                }

                $typeName       = Constants::getItemByKey('articles_title');


                if(!$Obj->blog){
                    $background     = 'style="background: '.$Obj->rubric->color.';"';
                    $border         = 'style="border: 5px solid '.$Obj->rubric->color.';"'; 
                    $typeName       = $Obj->rubric->transfer->name;
                    $sectionLink    = $Obj->rubric->getItemUrl();
                } else {
                    $background     = 'style="background: #961f20;"';
                    $border         = 'style="border: 5px solid #961f20;"'; 
                    $typeName       = Constants::getItemByKey('blogs_title');

                    $sectionLink    = Article::getSectionBlogUrl();
                }
                
            break;
            case 'Videos':  

                $isVideo = true;

                if(!empty($Obj->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Videos::PATH_IMAGE_SMALL.$Obj->image_filename)){
                    $img = Videos::PATH_IMAGE_SMALL.$Obj->image_filename;
                }


                $background     = 'style="background:#5d6f82;"';
                $border         = 'style="border: 5px solid #5d6f82;"';
                $typeName       = Constants::getItemByKey('video_title');
                $sectionLink    = Videos::getSectionUrl();

            break; 
            case 'Gallery':

                if(!empty($Obj->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Gallery::IMAGE_80x80.$Obj->image_filename)){
                    $img = Gallery::IMAGE_80x80.$Obj->image_filename;
                }

                $background     = 'style="background:#252429;"';
                $border         = 'style="border: 5px solid #252429;"';
                $typeName       = Constants::getItemByKey('photos_title');
                $sectionLink    = Gallery::getSectionUrl();

            break; 
        }//end switch


        $min_height = "";
        if($img != ''){
            list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'].$img);
            $min_height = "min-height:".$height."px;";
        }

        ?>  
        <div class="item item_froly" style="<?=$min_height?>"> <!-- item_flip-->
            <?php if($img != ''): ?>
                <div class="item-img">
                    <img src="<?=$img;?>" alt="<?=$Obj->transfer->name?>"/>
                    <?php if($isVideo):?>
                        <span></span>
                    <?php endif;?>
                </div>
            <?php endif; ?>
            <div class="item-title">
                <div <?=$background;?> class="line"></div>
                <div class="item-data">
                    <div class="item-comments">
                        <?=$Obj->comments_num;?>
                    </div>
                    <div class="item-views">
                        <?=$Obj->views_num;?>
                    </div>
                </div>
                 <a href="<?=$sectionLink?>" class="item-rubric">
                    <?=$typeName;?>
                </a>
            </div>
            <div class="item-text">
                <?=$objTitle;?>
            </div>
            <div class="item-text2">
                <?=$objAbsctract;?>
            </div>
            <a href="<?=$Obj->getItemUrl()?>" class="item-link"><div <?=$border;?> class="item-border"></div></a>
        </div>




<?php endforeach; ?>