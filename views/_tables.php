<?
use yii\helpers\Html;
use yii\grid\GridView;

?>

<?= GridView::widget([
    'dataProvider' => $provider,
    'columns' => [
        'name',

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => ' {update}',
            'buttons' => [
                'update' => function ($url,$model) {
                    return '<a  href="?table_module='.$model['name'].'" class="btn " ><span class="glyphicon glyphicon-screenshot"></span></a>';
                },
               
            ],
         ],
    ],
]); ?>