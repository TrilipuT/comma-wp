<?php error_reporting(E_ALL ^ E_NOTICE);


echo 1;

exit;

ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/backend.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
//Yii::createWebApplication($config)->run();
// стартуем приложение с помощью нашего WebApplicaitonEndBehavior, указав ему, что нужно загрузить бекэнд
Yii::createWebApplication($config)->runEnd('backend');


 








exit;

$sql     = "SELECT * FROM  `comma_gallery` WHERE  `image_filename` !=  '' ";
$command = Yii::app()->db->createCommand($sql);
$rows    = $command->queryAll();

Yii::import('application.components.Image');


foreach($rows as $row){

    $Image = new Image();
    $Image->load($_SERVER['DOCUMENT_ROOT'].Gallery::PATH_IMAGE_SRC.$row['image_filename']);
    $Image->scale(array(80,80))->save($_SERVER['DOCUMENT_ROOT'].Gallery::IMAGE_80x80.$row['id']);

}


exit;

$sql     = "SELECT c.* FROM comma_comment AS c WHERE c.votes_pro != 0 ";
$command = Yii::app()->db->createCommand($sql);
$rows    = $command->queryAll();
foreach($rows as $row){

    //var_dump($row['id']); echo "<br />";

    $votes_pro  = $row['votes_pro'];
    $_votes_pro = abs($votes_pro);

    if($_votes_pro == 0){
        continue;
    }

    $active = ($votes_pro > 0 ? 1 : 0);

    for($i = 0; $i < $_votes_pro; $i++){
        $insertSql = "INSERT INTO `comma_likes_count`
                            (`comment_id`, `user_id`, `tmp_user_id`, `active`, `ip`, `datetime`)
                     VALUES ( '".$row['id']."', '0', '0', '".$active."', '0', '0000-00-00 00:00:00');";

        $commandInner = Yii::app()->db->createCommand($insertSql);
        $commandInner->execute();
    }



}