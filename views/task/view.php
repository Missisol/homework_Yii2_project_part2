<?php

use app\models\TaskUser;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['my']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="task-view">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>
    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
      'class' => 'btn btn-danger',
      'data' => [
        'confirm' => 'Are you sure you want to delete this item?',
        'method' => 'post',
      ],
    ]) ?>
  </p>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      'id',
      'title',
      'description:ntext',
      'creator_id',
      'updater_id',
      'created_at',
      'updated_at',
    ],
  ]) ?>

  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
      [
        'label' => 'User name',
        'attribute' => 'title',
        'content' => function (TaskUser $model) {
          $user = $model->user->username;
          return $user;
        }
      ],
      [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{delete} ',
        'buttons' => [
          'delete' => function ($url, TaskUser $model, $key) {
            $icon = \yii\bootstrap\Html::icon('remove');
            return Html::a($icon,
              ['task-user/delete', 'id' => $model->id],
              ['data' => [
                'confirm' => 'Are you sure you want to delete this access?',
                'method' => 'post',
              ]
              ]
            );
          }
        ],
      ],
    ],
  ]) ?>

</div>
