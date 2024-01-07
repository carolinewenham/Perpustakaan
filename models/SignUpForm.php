<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class SignUpForm extends Model
{
    public $username;
    public $password;
    public $password_repeat;



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        //set rules untuk validasi
        return [
            [['username', 'password', 'password_repeat'], 'required'],
            [['username', 'password', 'password_repeat'], 'string', 'min' => 4, 'max' => 16],
            ['password_repeat', 'compare', 'compareAttribute' => 'password']

        ];
    }

    public function signUp()
    {
        //new user record
        $user = new User();
        $user->username = $this->username;
        // menggunakan  yii app security untuk  generate hash password
        $user->password = \Yii::$app->security->generatePasswordHash($this->password);
        // menggunakan  yii app security untuk  generate access_token
        $user->access_token = \Yii::$app->security->generateRandomString();
                // menggunakan  yii app security untuk  generate auth_key
        $user->auth_key = \Yii::$app->security->generateRandomString();

        if ($user->save()) {
            // berhasil registrasi
            return true;
            // message sukses di UI 
            Yii::$app->session->setFlash('success', 'Registrasi Berhasil');
        }
        \Yii::error("User is not save" . VarDumper::dumpAsString($user->errors));
        Yii::$app->session->setFlash('Error', 'Registrasi Gagal');
        return false;
    }
}
