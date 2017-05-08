<?php

namespace AdminBundle\Layout;

use AdminBundle\Component\Menu;

class Auth extends \fvLayout
{

    function __construct()
    {
        $this->view()->header = new Menu();

        $this->addJS( [
            "/assets/admin-bundle/jquery/jquery-2.0.3.min.js",
            "/assets/admin-bundle/jquery/plugins/jquery.mousewheel.js",
            "/assets/admin-bundle/jquery/plugins/jquery.fv.fadeUp.js",
            "/assets/admin-bundle/jquery/plugins/jquery.autosize.min.js",
            "/assets/admin-bundle/jquery/plugins/selectize.js",
            "/assets/admin-bundle/jquery/plugins/cropper.min.js",
            "/assets/admin-bundle/kube200/js/kube.buttons.js",
            "/assets/admin-bundle/kube200/js/kube.tabs.js",
            "/assets/admin-bundle/redactor/redactor.min.js",
            "/assets/admin-bundle/bootstrap-datepicker/js/bootstrap-datepicker.js",
            "/assets/admin-bundle/bootstrap-datepicker/js/locales/bootstrap-datepicker.ru.js",
            "/assets/admin-bundle/bootstrap-timepicker/js/bootstrap-timepicker.js",
            "/assets/admin-bundle/backend.js",
        ] );

        $this->addCSS( [
            "/assets/admin-bundle/kube200/css/kube.min.css",
            "/assets/admin-bundle/font-awesome/css/font-awesome.min.css",
            "/assets/admin-bundle/jquery/plugins/selectize.css",
            "/assets/admin-bundle/bootstrap-datepicker/css/dark.css",
            "/assets/admin-bundle/bootstrap-timepicker/css/dark.css",
            "/assets/admin-bundle/redactor/redactor.css",
            "/assets/admin-bundle/jquery/plugins/cropper.css",
            "/assets/admin-bundle/backend.css",
        ] );
    }

    function getFvVersion(){
        if( ! file_exists("../composer.lock")){
            return "";
        }

        $lockFile = json_decode(file_get_contents( "../composer.lock" ));

        foreach( $lockFile->packages as $key => $value ){
            if($value->name == "fv/core"){
                return $value->version;
            }
        }

        return null;
    }

}