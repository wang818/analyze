<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username 用户名
 * @property string $password 密码
 * @property string $auth_key --
 * @property string $access_token --
 * @property int $c_time 创建时间
 * @property int $l_time 最后登录时间
 * @property string $memo 备注
 * @property int $phone 手机号
 */
class AdminUser extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_time', 'phone'], 'required'],
            [['c_time', 'l_time', 'phone'], 'integer'],
            [['username', 'password', 'auth_key', 'access_token'], 'string', 'max' => 50],
            [['memo'], 'string', 'max' => 255],
            [['phone'], 'unique'],
            [['username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function validatePassword($password){
        return $this->password === md5($password);
    }

    public static function findByUsername($username){
        $user = AdminUser::find()
            ->where(['username' => $username])
            ->asArray()
            ->one();
        return new static($user);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'c_time' => 'C Time',
            'l_time' => 'L Time',
            'memo' => 'Memo',
            'phone' => 'Phone',
        ];
    }
}
