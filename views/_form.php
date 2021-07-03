<?
use dev2studi\database\models\Form;
use yii\widgets\{Pjax,ActiveForm};
use yii\helpers\Html;
 
 
?>


	<? $form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-horizontal'],
]) ?>



<ul class="nav nav-tabs">
  <li class="nav-item active">
    <a class="nav-link  " data-toggle="tab" href="#description">Таблица</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#characteristics">Настройки</a>
  </li>
  <li class="nav-item">
    <button type="submit" class="btn btn-light" style="    border-radius: 5px 5px 0px 0px;
    font-size: 16px;
    padding: 9px 35px;
    font-weight: bold;
    background: white;
    border: 1px solid #d2d2d2;">Создать</button>
  </li>
</ul>
<div class="tab-content">
  <div class="tab-pane fade in active" id="description">
   <table class="table table_s table-striped table-bordered">
	<thead>
		<tr>
			<th>Поле</th>
			<th>Подпись</th>
			<th>Тип редактирования</th>
			<th>Значение по умолчанию</th>
			<th>Связь</th>
			<th>Отбражать в таблице</th>
		</tr>
	</thead>
	<tbody>
	<? foreach ($provider as $key => $value) { ?>
		<tr>
			<td><?=$value['Field'];?></td>
			<td><?=$form->field($model, 'labels['.$value['Field'].']')->label(false);?></td>
			<td><?=$form->field($model, 'faileds['.$value['Field'].']')->dropDownList(Form::typeFormArray())->label(false);?></td>
			<td><?=$form->field($model, 'value['.$value['Field'].']')->label(false);?></td>
			<td><?=$form->field($model, 'connection['.$value['Field'].']')->label(false);?></td>
			<td><?=$form->field($model, 'tableFiled['.$value['Field'].']')->dropDownList(array(0=>'Нет',1=>'Да'))->label(false);?></td>
		</tr> 
	<? } ?>
</tbody>
</table>
  </div>
  <div class="tab-pane fade" id="characteristics">
  	<div class="col-md-12" style="background: #f5f5f5;">
  		<div class="objects"> 
   <?=$form->field($model, 'name')->label('Название таблицы');?>
	<?=$form->field($model, 'modeldir')->label('Путь к модели');?>
	<code>
			/modules/admin/models/
		</code>
	<?=$form->field($model, 'makedir')->label('Путь создания');?>
		<code>
			/modules/admin/
		</code>
	<?=$form->field($model, 'pjax_is')->dropDownList(array(0=>'Нет',1=>'Да'))->label('Использовать PJAX');?>
  </div></div>
  
</div></div>




 

 
<?php ActiveForm::end() ?>

<style>
	.table_s .form-group{
    width: 100%;
    margin: 0px;
	}

	.objects{
		    width: 100%;
    float: left;
	}

	.objects .form-group{
		    width: 100%;
    margin: 0px;
	}
</style>