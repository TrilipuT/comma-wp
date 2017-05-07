    <header class="header">
        <div class="header-fixed">
        <div class="header-inner">
            <a href="http://comma.com.ua/" target="_blank" class="header-logo2"></a>
            <div class="header-nav2">
                <a href="#" class="show_about_btn">О проекте</a>
                <a href="#" class="show_materials_btn">Материалы</a>
            </div>
            <a href="http://made-in-ukraine.comma.com.ua" class="header-logo-how"></a>
        </div>
        <div class="materials-overlay"></div>
        <div class="materials-wrapper">
            <div class="materials">
                <div class="close"></div>
                <div class="materials-inner">
                    <div class="next"></div>
                    <div class="prev"></div>
                    <div class="materials-content">
                        <ul class="floater">
                            <?php foreach ( $articles as $article ): ?>
                            <li class="item">
                                <div class="item-img">
                                    <a href="<?=$article->getItemUrl()?>"><img src="<?=Article::ICON150x150.$article->image_filename;?>" alt=""></a>
                                </div>
                                <div class="item-info">
                                    <div class="item-title">
                                        <a href="<?=$article->getItemUrl()?>"><?= $article->transfer->name ?></a>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="pages"></div>
                </div>
            </div>
        </div>
    </div>
    </header>