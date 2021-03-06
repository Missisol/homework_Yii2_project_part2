<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 * @property int $creator_id
 * @property int $updater_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Task[] $createdTasks
 * @property Task[] $updatedTasks
 * @property TaskUser[] $taskUsers
 *
 * @mixin TimestampBehavior
 * @mixin BlameableBehavior
 */
class User extends ActiveRecord implements IdentityInterface
{
  const RELATION_CREATED_TASKS = 'createdTasks';
  const RELATION_TASK_USERS = 'taskUsers';
  const SCENARIO_CREATE = 'create';
  const SCENARIO_UPDATE = 'update';
  public $password;

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'user';
  }

  /**
   * @return array
   *
   */
  public function behaviors()
  {
    return [
      ['class' => TimestampBehavior::class],
      ['class' => BlameableBehavior::class,
        'createdByAttribute' => 'creator_id',
        'updatedByAttribute' => 'updater_id',
      ],
    ];
  }

  /**
   * @param bool $insert
   * @return bool
   * @throws \yii\base\Exception
   */
  public function beforeSave($insert)
  {
    if (!parent::beforeSave($insert)) {
      return false;
    }
    if ($this->isNewRecord) {
      $this->auth_key = \Yii::$app->security->generateRandomString();
    }
    if ($this->password) {
      $this->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($this->password);
    }
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['username', 'password'], 'required', 'on' => self::SCENARIO_CREATE],
      [['username'], 'required', 'on' => self::SCENARIO_UPDATE],
      [['creator_id', 'updater_id', 'created_at', 'updated_at'], 'integer'],
      [['username', 'password', 'auth_key'], 'string', 'max' => 255],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'username' => 'Username',
      'password_hash' => 'Password Hash',
      'auth_key' => 'Auth Key',
      'creator_id' => 'Creator ID',
      'updater_id' => 'Updater ID',
      'created_at' => 'Created At',
      'updated_at' => 'Updated At',
    ];
  }

  /**
   * Finds an identity by the given ID.
   *
   * @param string|int $id the ID to be looked for
   * @return IdentityInterface|null the identity object that matches the given ID.
   */
  public static function findIdentity($id)
  {
    return static::findOne($id);
  }

  /**
   * Finds an identity by the given token.
   *
   * @param string $token the token to be looked for
   * @return IdentityInterface|null the identity object that matches the given token.
   */
  public static function findIdentityByAccessToken($token, $type = null)
  {
    return static::findOne(['access_token' => $token]);
  }

  /**
   * Finds user by username
   *
   * @param string $username
   * @return static|null
   */
  public static function findByUsername($username)
  {
    return User::findOne(['username' => $username]);
  }

  /**
   * Validates password
   *
   * @param string $password password to validate
   * @return bool if password provided is valid for current user
   */
  public function validatePassword($password)
  {
    return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
  }

  /**
   * @return int|string current user ID
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return string current user auth key
   */
  public function getAuthKey()
  {
    return $this->auth_key;
  }

  /**
   * @param string $authKey
   * @return bool if auth key is valid for current user
   */
  public function validateAuthKey($authKey)
  {
    return $this->getAuthKey() === $authKey;
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getCreatedTasks()
  {
    return $this->hasMany(Task::className(), ['creator_id' => 'id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getUpdatedTasks()
  {
    return $this->hasMany(Task::className(), ['updater_id' => 'id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getTaskUsers()
  {
    return $this->hasMany(TaskUser::className(), ['user_id' => 'id']);
  }

  public function getAccessedTask()
  {
    return $this->hasMany(Task::className(), ['id' => 'task_id'])
      ->via(User::RELATION_TASK_USERS);
  }

  /**
   * {@inheritdoc}
   * @return \app\models\query\UserQuery the active query used by this AR class.
   */
  public static function find()
  {
    return new \app\models\query\UserQuery(get_called_class());
  }
}
