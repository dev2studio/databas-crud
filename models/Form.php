<?php

namespace dev2studio\database\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Form extends Model
{
    public $name;
    public $table;
    public $faileds = [];
    public $value;
    public $connection = [];
    public $labels = [];
    public $dataFiled = [];
    public $tableFiled = [];

    public $namespaseModel;
    public $nameModelSearch;
    public $nameModel;
    public $modelID;


    public $modeldir;
    public $makedir;
    public $pjax_is;

    public static function typeFormArray()
    {
        return array(
            '-'=>'-',
            'text' => 'Текстовое',
            'textarea' => 'Большой текст',
            'textareaCkeditor' => 'Редактор текста',
            'number' => 'Числовое',
            'select' => 'Список',
            'file' => 'Файл',
            'checkbox' => 'Чекбокс лист',
            'checkbox_one' => 'Чекбокс',
            'radiobutton' => 'Радио cписок',
            'color' => 'Цвет',
            'date' => 'Дата',
            'datetime' => 'Дата и время',               
             
        );
    }

    public function generation()
    {
        $modelName = str_replace('_', '', $this->table);
        $modelName = mb_strtolower($modelName);
        $modelFiles = ucfirst($this->table);

        if($this->modeldir!=''){

            if (!file_exists(Yii::getAlias('@app').$this->modeldir)) {
                mkdir(Yii::getAlias('@app').$this->modeldir, 0777, true);
            }

            $this->creatModel(Yii::getAlias('@app').$this->modeldir.$modelFiles.'.php');
            $this->creatModelSearch(Yii::getAlias('@app').$this->modeldir.$modelFiles.'Search.php');

        }else{
            $this->creatModel(Yii::getAlias('@app').'/models/'.$modelFiles.'.php');
            $this->creatModelSearch(Yii::getAlias('@app').'/models/search/'.$modelFiles.'Search.php');
        }


        if($this->makedir!=''){

            if (!file_exists(Yii::getAlias('@app').$this->makedir.'/controllers')) {
                mkdir(Yii::getAlias('@app').$this->makedir.'/controllers', 0777, true);
            }

            if (!file_exists(Yii::getAlias('@app').$this->makedir.'/views')) {
                mkdir(Yii::getAlias('@app').$this->makedir.'/views', 0777, true);
            }

            $this->creatController(Yii::getAlias('@app').$this->makedir.'/controllers/'.$modelFiles.'Controller.php');
            $this->creatViews(Yii::getAlias('@app').$this->makedir.'/views/',mb_strtolower($modelFiles));

        }else{
            $this->creatController(Yii::getAlias('@app').'/controllers/'.$modelFiles.'Controller.php');
            $this->creatViews(Yii::getAlias('@app').'/views/',mb_strtolower($modelFiles));
        }

         


  
         



         
        // code...
    }

    public static function typeModels($value='')
    {
        $typeModels = explode('(',$value);

        return array(
            'type' => trim($typeModels[0]),
            'size' => (isset($typeModels[1])?str_replace(')','',$typeModels[1]):0)
        );

    }


    public function creatViews($dir='',$name)
    {
        $structure = $dir.$name;

        if(!is_dir($structure)){
            mkdir($structure, 0777, true);
        }

        $columnGrid = '';

 
        foreach($this->dataFiled as $filed => $value){
            if(isset($value['GRID']) AND $value['GRID']=='1'){
                 $columnGrid.='"'.$filed.'",';
            }
            
        }

        
 

$fileContent = '<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;


$this->title = "'.$this->name.'";
$this->params["breadcrumbs"][] = $this->title;
?>

<? //= Html::a("Создать '.$this->name.'" ["create"], ["class" => "btn btn-success"]) ?>

<div class="col-lg-12">
    <?php Pjax::begin(); ?>
    <?php // echo $this->render("_search", ["model" => $searchModel]); ?>
    <?= GridView::widget([
        "dataProvider" => $dataProvider,
        "filterModel" => $searchModel,
        "columns" => [
            "'.$this->modelID.'",
            '.$columnGrid.'
            ["class" => "yii\grid\ActionColumn","template" => "{update} {delete}"],
        ],
         
    ]); ?>
    <?php Pjax::end(); ?>
</div>
';
            $fp = fopen($structure.'/index.php', "w");
            fwrite($fp, $fileContent);
            fclose($fp);



$fileContent = '<?php
use yii\helpers\Html;
use kartik\file\FileInput;


$this->title = "Обновления '.$this->name.': " . $model->name;
$this->params["breadcrumbs"][] = ["label" => "'.$this->name.'", "url" => ["index"]];
$this->params["breadcrumbs"][] = "Обновления";
?>

<div class="col-md-12">
  <h1><?= Html::encode($this->title) ?></h1>
  <?= $this->render("_form", [
      "model" => $model,
  ]) ?>
</div>';
            $fp = fopen($structure.'/update.php', "w");
            fwrite($fp, $fileContent);
            fclose($fp);


$fileContent = '<?php
use yii\helpers\Html;

$this->title = "Создать '.$this->name.': " . $model->name;
$this->params["breadcrumbs"][] = ["label" => "'.$this->name.'", "url" => ["index"]];
$this->params["breadcrumbs"][] = "Создать";
?>

<div class="col-md-12">
  <h1><?= Html::encode($this->title) ?></h1>
  <?= $this->render("_form", [
      "model" => $model,
  ]) ?>
</div>';
            $fp = fopen($structure.'/create.php', "w");
            fwrite($fp, $fileContent);
            fclose($fp);



 
$form = '';

foreach ($this->dataFiled as $key => $value) {
   if(isset($value['TypeForm']) AND $value['TypeForm']!='-'){
    if($value['TypeForm']=='select'){

        $seleced = explode(',',$value['Value']);
        $stan = 0;

        if(isset($seleced[1])){
            $selecer = 'array(';
            foreach($seleced as $sel => $eo){
                $sele = explode(':',$eo);
                if(isset($sele[1])){
                    $selecer .='"'.$sele[0].'"=>"'.$sele[1].'",';
                }else{
                    $stan=1;
                }
                 
            }
            $selecer .=')';
        }else{
            $selecer = $value['Value'];
        }

        if($stan==1){
             $selecer = $value['Value'];
        }

        if(empty($selecer) OR $selecer==''){
            $selecer = "array('0'=>'Нет','1'=>'Да')";
        }

        $form .='<?=$form->field($model, "'.$key.'")->dropDownList('.$selecer.');?>'."\n";

    }elseif($value['TypeForm']=='radiobutton'){

        $seleced = explode(',',$value['Value']);
        $stan = 0;

        if(isset($seleced[1])){
            $selecer = 'array(';
            foreach($seleced as $sel => $eo){
                $sele = explode(':',$eo);
                if(isset($sele[1])){
                    $selecer .='"'.$sele[0].'"=>"'.$sele[1].'",';
                }else{
                    $stan=1;
                }
                 
            }
            $selecer .=')';
        }else{
            $selecer = $value['Value'];
        }

        if($stan==1){
             $selecer = $value['Value'];
        }

        if(empty($selecer) OR $selecer==''){
            $selecer = "array('0'=>'Нет','1'=>'Да')";
        }

        $form .='<?=$form->field($model, "'.$key.'")->radioList('.$selecer.');?>'."\n";

    }elseif($value['TypeForm']=='checkbox_one'){
         $form .='<?=$form->field($model, "'.$key.'")->checkbox(["label" => "'.$value['Labels'].'","value" => "'.$value['Value'].'"])->label(false);?>'."\n";
    }elseif($value['TypeForm']=='checkbox'){
        $seleced = explode(',',$value['Value']);
        $stan = 0;

        if(isset($seleced[1])){
            $selecer = 'array(';
            foreach($seleced as $sel => $eo){
                $sele = explode(':',$eo);
                if(isset($sele[1])){
                    $selecer .='"'.$sele[0].'"=>"'.$sele[1].'",';
                }else{
                    $stan=1;
                }
                 
            }
            $selecer .=')';
        }else{
            $selecer = $value['Value'];
        }

        if($stan==1){
             $selecer = $value['Value'];
        }

        if(empty($selecer) OR $selecer==''){
            $selecer = "array('0'=>'Нет','1'=>'Да')";
        }

        $form .='<?=$form->field($model, "'.$key.'")->checkboxList('.$selecer.');?>'."\n";

    }elseif($value['TypeForm']=='textarea'){
        $form .= '<?=$form->field($model, "'.$key.'")->textarea();?>'."\n";
    }elseif($value['TypeForm']=='date'){
         $form .= '<? echo "<label class='."'".'control-label'."'".'>'.$value['Labels'].'</label>";
   echo DatePicker::widget([
        "name" => "'.$this->nameModel.'['.$key.']",
        "pluginOptions" => [
                "todayHighlight"=>true,
                "format" => "yyyy-mm-dd"
        ]
   ]); ?>'."\n";
    }elseif($value['TypeForm']=='datetime'){
        $form .= '<? echo "<label class='."'".'control-label'."'".'>'.$value['Labels'].'</label>";
   echo DateTimePicker::widget([
        "name" => "'.$this->nameModel.'['.$key.']",
        "type" => DateTimePicker::TYPE_COMPONENT_APPEND,
        "pluginOptions" => [
                "autoclose"=>true,
                "format" => "yyyy-mm-dd H:i:s"
        ]
   ]); ?>'."\n";

     }elseif($value['TypeForm']=='color'){
        $form .= '<? echo "<label class='."'".'control-label'."'".'>'.$value['Labels'].'</label>";
   echo ColorInput::widget([
        "name" =>  "'.$this->nameModel.'['.$key.']",
        "options" => ["placeholder" => "Выберете цвет"]
   ]); ?>'."\n";

     }elseif($value['TypeForm']=='textareaCkeditor'){
        $form .= '<? echo $form->field($model, "'.$key.'")->widget(Summernote::class); ?>'."\n";
     }elseif($value['TypeForm']=='file'){
        $form .= '<?=$form->field($model, "'.$key.'")->widget(FileInput::classname());?>';
    }else{
        $form .='<?=$form->field($model, "'.$key.'");?>'."\n";
    }
     

   }
}



$fileContent = '<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\color\ColorInput;
use kartik\editors\Summernote;



?>

<?php $form = ActiveForm::begin([
        "options" => ["enctype" => "multipart/form-data"]
    ]); ?>

'.$form.'

<div class="form-group col-md-12">
        <?= Html::submitButton("Применить", ["class" => "btn btn-success"]) ?>
</div>

<?php ActiveForm::end(); ?>
';
            $fp = fopen($structure.'/_form.php', "w");
            fwrite($fp, $fileContent);
            fclose($fp);






 
    }



    public function creatController($dir='')
    {
        $modelName = '';
        $namespases= str_replace($_SERVER['DOCUMENT_ROOT'],'',$dir);
        $namespase = explode('/', $namespases);

        foreach ($namespase as $key => $value) {
            $chek = explode('.',$value);
            if(isset($chek[1])){
                $modelName=$chek[0];
                 unset($namespase[$key]);
            }
            if($value==''){
                 unset($namespase[$key]);
            }
        }

        $namespase = implode("\\", $namespase);




$fileContent ='<?php
namespace app\\'.$namespase.';

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use '.$this->namespaseModel.';
use '.$this->nameModelSearch.';

class '.$modelName.' extends Controller
{
    public function behaviors()
    {
        return [
            "verbs" => [
                "class" => VerbFilter::className(),
                "actions" => [
                    "delete" => ["POST"],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $searchModel = new '.$this->nameModel.'Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render("index", [
            "searchModel" => $searchModel,
            "dataProvider" => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new '.$this->nameModel.'();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(["index", "id" => $model->'.$this->modelID.']);
        }

        return $this->render("create", [
            "model" => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

       if ($model->load(Yii::$app->request->post()) && $model->update()) {
            return $this->redirect(["index", "id" => $model->'.$this->modelID.']);
        }



        return $this->render("update", [
           "model" => $model,
        ]);
    }


   public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = '.$this->nameModel.'::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException("The requested page does not exist.");
    }
    

}
';       
        $fp = fopen($dir, "w");
        fwrite($fp, $fileContent);
        fclose($fp);


       
    }



    public function creatModelSearch($dir=''){
        $modelName = '';
        $namespases= str_replace($_SERVER['DOCUMENT_ROOT'],'',$dir);
        $namespase = explode('/', $namespases);

        foreach ($namespase as $key => $value) {
            $chek = explode('.',$value);
            if(isset($chek[1])){
                $modelName=$chek[0];
                 unset($namespase[$key]);
            }
            if($value==''){
                 unset($namespase[$key]);
            }
        }

        $namespase = implode("\\", $namespase);
        

        $rullesNumber   = array();
        $rullseText     = array();
        $rullseSafe     = array();
        $rullseTextLong = array();

        foreach($this->dataFiled as $filed => $value){
            if($value['Key']!='PRI'){
                if($value['Type']=='int'){
                    $rullesNumber[]=$value['Field'];
                }elseif($value['Type']=='varchar'){
                    $rullseText[]=$value['Field'];
                }elseif($value['Type']=='text'){
                    $rullseTextLong[]=$value['Field'];
                }else{
                    $rullseSafe[]=$value['Field'];
                }
            } 
        }

$rules = 'public function rules()
    {
        return [
          '.(count($rullesNumber)>=1?'[["'.implode('","',$rullesNumber).'"],"number"],':'').'
          '.(count($rullseText)>=1?'[["'.implode('","',$rullseText).'"],"string"],':'').'
          '.(count($rullseSafe)>=1?'[["'.implode('","',$rullseSafe).'"],"safe"],':'').'
        ];
 
    }';

$this->nameModelSearch = ' app\\'.$namespase.'\\'.$modelName;

$fileContent ='<?php
namespace app\\'.$namespase.';

use yii\base\Model;
use yii\data\ActiveDataProvider;
use '.$this->namespaseModel.';

class '.$modelName.' extends '.$this->nameModel.'
{
     

    '.$rules.'

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function search($params)
    {
        $query = '.$this->nameModel.'::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            "query" => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) { 
            return $dataProvider;
        }

        $query->andFilterWhere([
            "'.$this->modelID.'" => $this->'.$this->modelID.',
         ]);

         return $dataProvider;
    }

}
';       
        $fp = fopen($dir, "w");
        fwrite($fp, $fileContent);
        fclose($fp);

    }





    public function creatModel($dir='')
    {
        $modelName = '';
        $namespases= str_replace($_SERVER['DOCUMENT_ROOT'],'',$dir);
        $namespase = explode('/', $namespases);

        foreach ($namespase as $key => $value) {
            $chek = explode('.',$value);
            if(isset($chek[1])){
                $modelName=$chek[0];
                 unset($namespase[$key]);
            }
            if($value==''){
                 unset($namespase[$key]);
            }
        }

        $namespase = implode("\\", $namespase);

        $dataFiled = array();


        $providers =Yii::$app->db->createCommand("DESCRIBE    `".$this->table."`")->queryAll();
        foreach($providers as $provider){
            $dataFiled[$provider['Field']] = $provider;

        }

        $rullesNumber   = array();
        $rullseText     = array();
        $rullseSafe     = array();
        $rullseTextLong = array();

        foreach($dataFiled  as $filed => $value){
            $typeModels = Form::typeModels($value['Type']);
            $dataFiled[$filed]['Type'] = $typeModels['type'];
            $dataFiled[$filed]['Size'] = $typeModels['size'];

             if(isset($this->tableFiled[$filed]) AND $this->tableFiled[$filed]=='1'){
                $dataFiled[$filed]['GRID'] = 1;
            }
 
            if(isset($this->faileds[$filed])){
                $dataFiled[$filed]['TypeForm'] = $this->faileds[$filed];
            }

            if(isset($this->labels[$filed])){
                $dataFiled[$filed]['Labels'] = $this->labels[$filed];
            }

            if(isset($this->value[$filed])){
                $dataFiled[$filed]['Value'] = $this->value[$filed];
            }

            if(isset($this->connection[$filed])){
                $dataFiled[$filed]['Connection'] = $this->connection[$filed];
            }


        }

        $this->dataFiled = $dataFiled;



        foreach($dataFiled as $filed => $value){
            if($value['Key']!='PRI'){
                if($value['Type']=='int'){
                    $rullesNumber[]=$value['Field'];
                }elseif($value['Type']=='varchar'){
                    $rullseText[]=$value['Field'];
                }elseif($value['Type']=='text'){
                    $rullseTextLong[]=$value['Field'];
                }else{
                    $rullseSafe[]=$value['Field'];
                }
            }else{
                $this->modelID =$value['Field'];
            }
        }


$rules = 'public function rules()
    {
        return [
          '.(count($rullesNumber)>=1?'[["'.implode('","',$rullesNumber).'"],"number"],':'').'
          '.(count($rullseText)>=1?'[["'.implode('","',$rullseText).'"],"string"],':'').'
          '.(count($rullseSafe)>=1?'[["'.implode('","',$rullseSafe).'"],"safe"],':'').'
        ];
 
    }';



        $labelsAr = array();

        foreach($dataFiled as $filed => $value){

            if(isset($value['Labels'])){
                $labelsAr[$filed]=$value['Labels'];
            }

        }


if(count($labelsAr)>=1){

$labels = 'public function attributeLabels()
    {
        return ['."\n";

        foreach($labelsAr as $key =>$labesls){
$labels .='         "'.$key.'"=>"'.$labesls.'",'."\n";
        }

 $labels .='        ];
 
    }';

}else{
    $labels = '';
}


$this->namespaseModel = 'app\\'.$namespase.'\\'.$modelName;
$this->nameModel = $modelName;

$fileContent ='<?php
namespace app\\'.$namespase.';

use Yii;

class '.$modelName.' extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return "'.$this->table.'";
    }

    '.$rules.'

    '.$labels.'

}
';       
        $fp = fopen($dir, "w");
        fwrite($fp, $fileContent);
        fclose($fp);
    }







 

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name','table'], 'required'],
            [['faileds','value','connection','makedir','pjax_is','labels','modeldir','tableFiled'], 'safe'],
            
        ];
    }

   
}
