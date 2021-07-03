<?php

namespace dev2studio\database;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use dev2studi\database\models\Form;
/**
 * This is just an example.
 */
class D2SCrud extends \yii\base\Widget
{
     


    public function run()
    {
        $get = Yii::$app->request->get();

        $tables = array();
        $provider = array();

        $model = new Form;


        if ($model->load(Yii::$app->request->post())) {
            $model->table = $get['table_module'];
            $model->generation();
            unset($get['table_module']);
         }





        if(isset($get['table_module'])){

            $providers =Yii::$app->db->createCommand("DESCRIBE    `".$get['table_module']."`")->queryAll();

            foreach($providers as $provid){
                if($provid['Key']!='PRI'){
                    $provider[] = $provid;
                }
             }

 

        }else{
            foreach (Yii::$app->db->createCommand('SHOW TABLES')->queryAll() as $table) {
                $tables[]=array(
                    'name'=>array_shift($table)
                );
            }

            $provider = new ArrayDataProvider([
                'allModels' => $tables,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        }
 
        
         

        return $this->renderFile(__DIR__."/views/run.php", ['provider'=>$provider,'get'=>$get,'model'=>$model]);
    }
}
