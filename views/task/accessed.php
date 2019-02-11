<?php

use app\models\Task;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Accessed Tasks';
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['accessed']];
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
//      'creator.username',
      [
        'label' => 'Task Author Name',
        'attribute' => 'title',
        'content' => function (Task $model) {
          $user = $model->creator->username;
          return $user;
        }
      ],
      'created_at:datetime',
    ],
  ]); ?>
  <?php Pjax::end(); ?>
</div>
