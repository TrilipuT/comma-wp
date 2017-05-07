<?php

return CMap::mergeArray(

    require_once(dirname(__FILE__).'/main.php'),
    
    array( 
        
        // стандартный контроллер
        'defaultController' => 'support',
        'name'              => 'COMMA',
        'language'          => 'ru',  
        // компоненты
        'components'=>array(
             
            'user'=>array(
                'allowAutoLogin'=>true,
                 'loginUrl'=> '/support/login',
            ), 

      		// mailer
      		'mailer'=>array(
        		'pathViews' => 'application.views.backend.email',
        		'pathLayouts' => 'application.views.email.backend.layouts'
      		),
            'errorHandler'=>array(
                // use 'site/error' action to display errors
                'errorAction'=>'support/error',
            ),

            'urlManager'=>array(
                //'class'             => 'application.components.AdminUrlManager',
                'urlFormat'         => 'path',
                'showScriptName'    => false,
                'rules'=>array(
                    
                    'support(/index)?'                          => 'support/index',
                    'support/login'                             => 'support/login', //<id:\d+>
                    'support/logout'                            => 'support/logout',
                    'support/ajax/<action:\w+>'                 => 'ajax/<action>',
                    
                    'support/send_mail'                         => 'send_mail/index',
                    
                    'support/preview(/<model_name>(/<id:\d+>/?)?)?'=> 'preview/index', 
                    
                    'support/settings'                          => 'settings/index',
                    'support/settings/<action:\w+>(/<id:\d+>)?' => 'settings/<action>',
                   

                    'support/<model_name>(/<page:\d+>)?'        => 'support/index',  //?action=index

                    'support/<action:\w+>/editors'              => 'editors/<action>',
                    'support/<action:\w+>/editors/<id:\d+>(/)?' => 'editors/<action>',

                    'support/<action:\w+>/<model_name:\w+>'     => 'support/<action>',
                    'support/<action:\w+>/<model_name:\w+>/<id:\d+>(/)?'=>'support/<action>',

                ),
            ),

        ),
    )
);