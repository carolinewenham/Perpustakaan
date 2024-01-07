<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "User".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 */

class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'User';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'auth_key', 'access_token'], 'required'],
            [['username', 'password', 'auth_key', 'access_token'], 'string', 'max' => 255],


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
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
        ];
    }

    public static function findIdentity($id)
    {
        return self::find()->where(['id' => $id])->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::find()->where(['access_token' => $token])->one();
    }

    public static function getUser()
    {
        $models = User::find()->all();
        $result = [];
        foreach ($models as $model) {
            $result[$model->id] = $model->username;
        }
        return $result;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    public static function getUsername($id){
        $model = User::findOne($id);
        return $model->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }
}

// class User extends ActiveRecord implements \yii\web\IdentityInterface
// {
    

//     // public static function tableName()
//     // {
//     //     return 'user';
//     // }


//     // /**
//     //  * {@inheritdoc}
//     //  */
//     // public static function findIdentity($id)
//     // {
//     //     return self::find()->where(['id' => $id])->one();
//     // }

//     // /**
//     //  * {@inheritdoc}
//     //  */
//     // public static function findIdentityByAccessToken($token, $type = null)
//     // {
//     //     return self::find()->where(['access_token' => $token])->one();
//     // }

//     // /**
//     //  * Finds user by username
//     //  *
//     //  * @param string $username
//     //  * @return static|null
//     //  */
//     // public static function findByUsername($username)
//     // {
//     //     return self::findOne(['username' => $username]);
//     // }

//     // /**
//     //  * {@inheritdoc}
//     //  */
//     // public function getId()
//     // {
//     //     return $this->id;
//     // }

//     // /**
//     //  * {@inheritdoc}
//     //  */
//     // public function getAuthKey()
//     // {
//     //     return $this->auth_key;
//     // }

//     // /**
//     //  * {@inheritdoc}
//     //  */
//     // public function validateAuthKey($authKey)
//     // {
//     //     return $this->auth_key === $authKey;
//     // }

//     // /**
//     //  * Validates password
//     //  *
//     //  * @param string $password password to validate
//     //  * @return bool if password provided is valid for current user
//     //  */
//     // public function validatePassword($password)
//     // {
//     //     return $this->password === $password;
//     // }
// }
