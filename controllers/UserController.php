<?php

namespace app\controllers;

use app\models\Task;
use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
  /**
   * {@inheritdoc}
   */
  public function behaviors()
  {
    return [
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['POST'],
        ],
      ],
      'access' => [
        'class' => AccessControl::className(),
        'rules' => [
          [
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
    ];
  }

  /**
   * Lists all User models.
   * @return mixed
   */
  public function actionIndex()
  {
    $dataProvider = new ActiveDataProvider([
      'query' => User::find(),
    ]);

    return $this->render('index', [
      'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * Displays a single User model.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionView($id)
  {
    return $this->render('view', [
      'model' => $this->findModel($id),
    ]);
  }

  /**
   * Creates a new User model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
  {
    $model = new User();
    $model->setScenario(User::SCENARIO_CREATE);
//    $model->creator_id = '1';

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->id]);
    }

    return $this->render('create', [
      'model' => $model,
    ]);
  }

  /**
   * Updates an existing User model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionUpdate($id)
  {
    $model = $this->findModel($id);
    $model->setScenario(User::SCENARIO_UPDATE);

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->id]);
    }

    return $this->render('update', [
      'model' => $model,
    ]);
  }

  /**
   * Deletes an existing User model.
   * If deletion is successful, the browser will be redirected to the 'index' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionDelete($id)
  {
    $this->findModel($id)->delete();

    return $this->redirect(['index']);
  }

  /**
   * Finds the User model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return User the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if (($model = User::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException('The requested page does not exist.');
  }

  public function actionTest()
  {
    $user = new User();
    $user->username = 'User3';
    $user->password_hash = 'asdsdsfa';
    $user->creator_id = '1';
    $user->created_at = time();
    $user->save();

    $user1 = User::findOne(77);
    $task1 = new Task();
    $task1->title = 'First';
    $task1->description = 'dgdgdgdgdgd';
    $task1->created_at = time();
    $task1->link(Task::CREATOR_TASK, $user1);

    $user2 = User::findOne(78);
    $task2 = new Task();
    $task2->title = 'Second';
    $task2->description = 'ggggggg';
    $task2->created_at = time();
    $task2->link(Task::CREATOR_TASK, $user2);

    $user3 = User::findOne(79);
    $task3 = new Task();
    $task3->title = 'Third';
    $task3->description = 'ddddddddddddd';
    $task3->created_at = time();
    $task3->link(Task::CREATOR_TASK, $user3);

    User::find()->with(User::RELATION_CREATED_TASKS)->all();

    User::find()->joinWith(User::RELATION_CREATED_TASKS)->all();


    $user5 = new User();
    $user5->username = 'user_new';
    $user5->password_hash = 'hhhhhhhhhhh';
    $user5->creator_id = '2';
    $user5->created_at = time();
    $user5->save();
    $task5 = new Task();
    $task5->title = 'Third';
    $task5->description = 'ddddddddddddd';
    $task5->created_at = time();
    $task5->link(Task::CREATOR_TASK, $user5);
    $user5->link('accessedTask', $task5);

    return $this->render('test');
  }
}
