<?
use yii\widgets\{Pjax,ActiveForm};
use yii\helpers\Html;
use yii\grid\GridView;
 
?>
<?php Pjax::begin(['enablePushState' => false]); 
if(isset($get['table_module'])){
	echo \Yii::$app->view->renderFile(__DIR__."/_form.php", ['model'=>$model,'get'=>$get,'provider'=>$provider]);
}else{ 
	echo \Yii::$app->view->renderFile(__DIR__."/_tables.php", ['provider'=>$provider]);

}

Pjax::end(); ?>
