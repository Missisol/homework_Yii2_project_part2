<?php

use app\models\Task;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Shared Tasks';
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['shared']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

  <h1><?= Html::encode($this->title) ?></h1>
  <?php Pjax::begin(); ?>

  <p>
    <?= Html::a('Create Task', ['create'], ['class' => 'btn btn-success']) ?>
  </p>

  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],

      'title',
      'description:ntext',
      'created_at:datetime',
      'updated_at:datetime',
      [
        'label' => 'Users',
        'attribute' => 'title',
        'content' => function (Task $model) {
          $users = $model->getSharedUsers()->select('username')->column();
          return join(', ', $users);
        }
      ],

      [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{deleteAll} {view} {update} {delete}',
        'buttons' => [
          'deleteAll' => function ($url, Task $model, $key) {
            $icon = \yii\bootstrap\Html::icon('remove');
            return Html::a($icon,
              ['task-user/delete-all', 'taskId' => $model->id],
              ['data' => [
                'confirm' => 'Are you sure you want to delete all accesses?',
                'method' => 'post',
                 ]
              ]
            );
          }
        ],
      ],
    ],
  ]); ?>
  <?php Pjax::end(); ?>
</div>
