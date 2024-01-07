<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\KatalogBuku;
use app\models\Peminjam;
use app\models\Pengembalian;
use app\models\SignUpForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                  //untuk autentikasi action logout dan dashboard  hanya dapat diakses oleh user yang login
                'only' => ['logout','dashboard'],
                'rules' => [
                    [
                        'actions' => ['logout','dashboard'],
                        'allow' => true,
                        'roles' => ['@'],// all login user
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect('site/dashboard');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionDashboard()
    {
        //query buku dengan status 1 / tersedia lalu di dihitung dengn count
        $modelBuku = KatalogBuku::find()->where(['status' => 1])->all() ;
        $countBuku = count($modelBuku);
       //query buku dengan status 0 / dipinjam lalu di dihitung dengn count
        $modelBukuDipinjam = KatalogBuku::find()->where(['status' => 0])->all() ;
        $countBukuDipinjam = count($modelBukuDipinjam);
        //query peminjam dengan status 1  / peminjam lalu di dihitung dengn count
        $modelPeminjam = Peminjam::find()->where(['status' => 1])->all();
        $countModelPeminjam = count($modelPeminjam);
       //query pengembalian lalu di dihitung dengn count
        $modelPengembalian = Pengembalian::find()->all();
        $countModelPengembalian = count($modelPengembalian);
        return $this->render('dashboard',[
            'countBuku' => $countBuku,
            'countBukuDipinjam' => $countBukuDipinjam,
            'countModelPeminjam' => $countModelPeminjam,
            'countModelPengembalian' => $countModelPengembalian
        ]);
    }
    
    public function actionSignUp(){

        //new record on sign up form
        $model = new SignUpForm();
        // cek jika req post 
        //model->signup adalah fungsi untuk register user pada model signupform
        if($model->load(Yii::$app->request->post())  && $model->signup()){
            return $this->redirect(Yii::$app->homeUrl);
        }
        return $this->render('signup',[
            'model' => $model
        ]);
    }
}
