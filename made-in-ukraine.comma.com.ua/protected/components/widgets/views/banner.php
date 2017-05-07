<?php /* if($Banners && !empty($Banners->file_banner) && file_exists($_SERVER['DOCUMENT_ROOT'].Banners::PATH_BANNER.$Banners->file_banner) ):?>
  
    <a href="#" class="banner">
            <object 
                    classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" 
                    codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" 
                    width="<?=$Banners->width?>" 
                    height="<?=$Banners->height?>"
                    id="<?=$Banners->file_banner?>"
                    align="middle">

            <param name="allowScriptAccess" value="sameDomain" />
            <param name="allowFullScreen" value="false" />
            <param name="movie" value="<?=Banners::PATH_BANNER.$Banners->file_banner?>?link1=<?=$Banners->target_url?>" />
            <param name="quality" value="high" />
            <embed 
                src="<?=Banners::PATH_BANNER.$Banners->file_banner?>?link1=<?=$Banners->target_url?>" 
                quality="high" 
                width="<?=$Banners->width?>" 
                height="<?=$Banners->height?>" 
                name="<?=$Banners->file_banner?>" 
                align="middle" 
                allowScriptAccess="sameDomain" 
                allowFullScreen="false" 
                type="application/x-shockwave-flash" 
                pluginspage="http://www.adobe.com/go/getflashplayer" />
            </object> 
    </a> 
<?phpendif; */ ?> 



<?php if($Banners):
	$block_height = '';
	$ext = explode('.', $Banners->file_banner);
	$ext = $ext[count($ext)-1];

	if($ext != 'swf' && !empty($Banners->file_banner) && file_exists($_SERVER['DOCUMENT_ROOT'].Banners::PATH_BANNER.$Banners->file_banner)){
		$size = getimagesize($_SERVER['DOCUMENT_ROOT'].Banners::PATH_BANNER.$Banners->file_banner);
		$width = $size[0];
		$height = $size[1];
		$block_height = 'height:' . $height . 'px;';
	}
	?>
    
    <div class="banner" style="<?=$block_height;?>">

        <?php if(!empty($Banners->banner_code)):?>
            <?=$Banners->banner_code?>
        <?php elseif(!empty($Banners->file_banner) && file_exists($_SERVER['DOCUMENT_ROOT'].Banners::PATH_BANNER.$Banners->file_banner) ):
           
            $ext = explode('.', $Banners->file_banner);
            $ext = $ext[count($ext)-1];


            if($ext == 'swf'):?> 

                <script type="text/javascript"> 
                    /*
                    $(document).ready(function(){

                        //console.log(isFlashEnabled());
                         
                        if(!isFlashEnabled()){
                            var idBanner = 'file_banner_<?=$Banners->id?>';
                            $('#'+idBanner).remove();

                            var idStaticImg = "file_banner_static_<?=$Banners->id?>";
                            $('#'+idStaticImg).show();
                        }
                    });*/

                </script>
                    <?php if(!empty($Banners->file_banner_static) && file_exists($_SERVER['DOCUMENT_ROOT'].Banners::PATH_IMAGE.$Banners->file_banner_static) ): ?>
                        <div id="file_banner_static_<?=$Banners->id?>" style="display:none;">
                            <img class="banner__img" init-width="<?=$Banners->width?>" width="100%<?=''//$Banners->width?>" init-height="<?=$Banners->height?>"  src="<?=Banners::PATH_IMAGE.$Banners->file_banner_static?>" alt="<?=$Banners->name?>" />
                        </div>
                    <?php endif;?>

                    <div id="file_banner_<?=$Banners->id?>" style="height:<?=$Banners->height?>px;width:<?=$Banners->width?>px">
                        <object 
                                codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" 
                                width="100%" 
                                height="100%"
                                id="<?=$Banners->file_banner?>_2"
                                align="middle" data-init_width="<?=$Banners->width?>" data-init_height="<?=$Banners->height?>" class="banner__flash">

                            <param name="wmode" value="transparent">
                            <param name="allowScriptAccess" value="sameDomain" />
                            <param name="allowFullScreen" value="false" />
                            <param name="movie" value="<?=Banners::PATH_BANNER.$Banners->file_banner?><?=($Banners->target_url != null ? '?link1='.urlencode($Banners->target_url) : '')?>" />
                            <param name="quality" value="high" />
                            <embed 
                                src="<?=Banners::PATH_BANNER.$Banners->file_banner?><?=($Banners->target_url != null ? '?link1='.urlencode($Banners->target_url) : '')?>" 
                                quality="high" 
                                width="100%" 
                                height="100%" 
                                wmode="transparent" 
                                name="<?=$Banners->file_banner?>" 
                                align="middle" 
                                allowScriptAccess="sameDomain" 
                                allowFullScreen="false" 
                                type="application/x-shockwave-flash" 
                                pluginspage="http://www.adobe.com/go/getflashplayer" />
                        </object>
                    </div>
            <?php else:?>

                <?php if($Banners->target_url == ''):?>
                    <img class="banner__img" init-width="<?=$Banners->width?>" width="100%<?=''//$Banners->width?>" init-height="<?=$Banners->height?>"  src="<?=Banners::PATH_BANNER.$Banners->file_banner?>" alt="<?=$Banners->name?>" />
                <?php else:
					if(substr($Banners->target_url, 0, 3) == 'www'){
						$Banners->target_url = 'http://' . $Banners->target_url;
					}
					?>
                    <a href="<?=$Banners->target_url?>" target="_blank">
                        <img class="banner__img" init-width="<?=$Banners->width?>" width="100%<?=''//$Banners->width?>" init-height="<?=$Banners->height?>"  src="<?=Banners::PATH_BANNER.$Banners->file_banner?>" alt="<?=$Banners->name?>" />
                    </a>
                <?php endif;?>

            <?php endif;?>


        <?php endif;?>

    </div>
<?php elseif(!empty($Banners->banner_code) ):?>

    <div class="banner">
        <?=$Banners->banner_code;?>
    </div>
<?php endif;?>  