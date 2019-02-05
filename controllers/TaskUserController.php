<?php

namespace app\controllers;

use app\models\Task;
use app\models\User;
use Yii;
use app\models\TaskUser;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskUserController implements the CRUD actions for TaskUser model.
 */
class TaskUserController extends Controller
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
          'deleteAll' => ['POST'],
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
   * Creates a new TaskUser model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   * @var $users array
   * @throws ForbiddenHttpException
   */

  public function actionCreate($taskId)
  {
    $task = Task::findOne($taskId);
    if (!$task || $task->creator_id != Yii::$app->user->id) {
      throw new ForbiddenHttpException();
    }

    $model = new TaskUser();
    $model->task_id = $taskId;

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      Yii::$app->session->setFlash('success', 'Task shared successfully');

      return $this->redirect(['task/shared']);
    }

    // Подзапрос для нахождения пользователей, которым уже назначена эта задача
    $taskUsers = TaskUser::find()
      ->where(['=', 'task_id', $taskId])
      ->select('user_id')->column();

    // Запрос для нахождения пользователей, не являющихся создателями данной задачи,
    // и которым данная задача еще не назначена
    $users = User::find()
      ->where([
        'and',
        ['<>', 'id', Yii::$app->user->id],
        ['not in', 'id', $taskUsers]
      ])
      ->select('username')->indexBy('id')->column();


    return $this->render('create', [
      'model' => $model,
      'users' => $users,
    ]);
  }

  /**
   * @param $taskId
   * @return \yii\web\Response
   * @throws ForbiddenHttpException
   */
  public function actionDeleteAll($taskId)
  {
    $task = Task::findOne($taskId);
    if (!$task || $task->creator_id != Yii::$app->user->id) {
      throw new ForbiddenHttpException();
    }

    $task->unlinkAll(Task::RELATION_TASK_USERS, true);

    Yii::$app->session->setFlash('success', 'Accesses deleted successfully');

    return $this->redirect(['task/shared']);
  }

  /**
   * Deletes an existing TaskUser model.
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
   * Finds the TaskUser model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return TaskUser the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if (($model = TaskUser::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException('The requested page does not exist.');
  }
}
