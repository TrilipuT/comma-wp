<div class="container_for_grid margin_bottom_20 clearfix">
    <div class="grid_3of4">
        <div class="single_news">
            <div class="single_news-title">
                <?=$Section->transfer->name?>
            </div>
 
            <div class="single_news-content"> 
                <?=$Section->textOutput($Section->transfer->content)?>
            </div>

            <div class="likes_n_tags"> 
                <?php $this->widget('application.components.widgets.Socials'); ?> 
            </div>  
              
        </div>
    </div>
    <div class="grid_1of4 grid_last">
        <div class="scroll_switcher">
            <div> 
                <div class="scroll_switcher-item">
                    <?php $this->widget('application.components.widgets.Banner'); ?> 
                </div> 
            </div>
        </div>
    </div>
</div>  