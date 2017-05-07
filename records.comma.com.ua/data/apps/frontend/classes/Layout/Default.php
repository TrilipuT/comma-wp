<?php

class Layout_Default extends fvLayout
{
    protected $header;

    function __construct()
    {
        $this->view()->lang = Language::getManager()->getCurrentLanguage()->code;
        $this->view()->header = $this->header = new Component_Header();
        $this->view()->shareTitle = fvSite::dictionary()->translate("shareTitle");
        $this->view()->shareDescription = fvSite::dictionary()->translate("shareDescription");
        $this->view()->shareImage = "/images/share.jpg";

        $this->addCSS([
            "/theme/stylesheets/styles.css"
        ]);

        $this->addJS([
            fvUrlGenerator::get("javascript:config"),
            "/libs/jquery/jquery-2.0.3.min.js",
            "/libs/social/social.js",
            "/libs/jquery/plugins/jquery.cookie.js",
            "/libs/jquery/plugins/jquery.debounce-1.0.5.js",
            "/theme/scripts/common.js",
        ]);
    }

    public function getHeader(){
        return $this->header;
    }

}