<?php

namespace app\controllers;

use app\models\Product;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;

class TestController extends Controller
{
  /**
   * @return string
   */
  public function actionIndex()
  {


  }

  /**
   * @return string
   * @throws \yii\db\Exception
   */
  public function actionInsert()
  {
    Yii::$app->db->createCommand()->insert('user', [
      'username' => 'user1',
      'password_hash' => 'asdasd',
      'created_at' => time(),
    ])->execute();

    Yii::$app->db->createCommand()->insert('user', [
      'username' => 'user2',
      'password_hash' => 'qwerty',
      'created_at' => time(),
    ])->execute();

    Yii::$app->db->createCommand()->insert('user', [
      'username' => 'user3',
      'password_hash' => 'zxczxc',
      'created_at' => time(),
    ])->execute();

    Yii::$app->db->createCommand()
      ->batchInsert('task', ['title', 'description', 'creator_id', 'created_at'], [
        ['task1', 'Description1', 2, time()],
        ['task2', 'Description2', 3, time()],
        ['task3', 'Description3', 4, time()],
      ])->execute();

    return $this;
  }

  /**
   * @return string
   */
  public function actionSelect()
  {
    $id = 1;

    $result = (new Query())->from('user')
      ->where(['id' => $id])->one();

    $sort = (new Query())->from('user')->where(['>', 'id', $id] )
      ->orderBy('username DESC')->all();

    $count = (new Query())->from('user')->count();

    $userTask = (new Query())->select([
      'task_id' => 't.id',
      'title' => 't.title',
      'description' => 't.description',
      'task_creator_id' => 't.creator_id',
      'task_updater_id' => 't.updater_id',
      'task_created_at' => 't.created_at',
      'task_updated_at' => 't.updated_at',
      'user_id' => 'u.id',
      'username' => 'u.username',
      'user_creator_id' => 'u.creator_id',
      'user_updater_id' => 'u.updater_id',
      'user_created_at' => 'u.created_at',
      'user_updated_at' => 'u.updated_at',
      ])
      ->from(['t' => 'task'])
      ->innerJoin(['u' => 'user'], 'u.id = t.creator_id')->all();

    return $this->render('select', [
      'result' => $result,
      'sort' => $sort,
      'count' => $count,
      'userTask' => $userTask,
    ]);
  }
}