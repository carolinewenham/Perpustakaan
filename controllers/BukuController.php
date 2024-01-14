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
use app\models\SignUpForm;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

class BukuController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {

        //untuk autentikasi action list buku dan create update buku hanya dapat diakses oleh user yang login
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['list-buku', 'create-update-buku'],
                'rules' => [
                    [
                        'actions' => ['list-buku', 'create-update-buku'],
                        'allow' => true,
                        'roles' => ['@'], //l log-in user
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

    public function actionListBuku()
    {

        $model = KatalogBuku::find()->all();
        //diatas untuk menampilkan semua data yang ada dalam model katalog buku 
        //find()-> adalah metode yang disediakan oleh Yii2 ActiveRecord untuk membuat objek kueri untuk tabel buku.
        //all-> untuk mengambil semua record yang ada di tabel buku
        //render view list buku dengan pass model agar dapat ditampilkan di view
        return $this->render('list-buku', [
            'model' => $model
        ]);
    }
    public function actionCreateUpdateBuku($id = '')
    {
        // cek apakah ada id pada url jika ada maka update jika tidak ada create 
        if ($id) {
            //untuk mengambil 1 record buku dengan id dari parameter
            $model = KatalogBuku::findOne($id);
            $title = 'Update Buku';
        } else {
            //new katalog buku = untuk membuat record baru pada tabel buku
            $model = new KatalogBuku();
            $title = 'Add Buku';
        }
        try {
            //cek apakah request post
            if ($model->load(Yii::$app->request->post())) {
                if (!$id) {
                    //jika create masukkan data created by dan status 
                    // status = 1 artinya buku tersedia
                    $model->created_by = Yii::$app->user->id;
                    $model->status = 1;
                }
                $webroot = Yii::getAlias('@webroot/data');
                // upload file
                $model->profile_picture = UploadedFile::getInstance($model, 'profile_picture');
                if ($model->profile_picture) {
                    // cek apakah ada folder data
                    if (is_dir(Yii::$app->basePath . "/web/data")) {
                        // put content dengan sesuai dengan webroot. file name dan diisi dengan content dari model->profile_picture ( File)
                        if (file_put_contents(Yii::getAlias('@webroot/data/') . $model->fix_picture,  file_get_contents($model->profile_picture->tempName)) !== false) {
                            // Profile_picture diisi dengan fix_picture (filename) saja untuk disimpan ke database
                            $model->profile_picture = $model->fix_picture;
                        } else {
                            // error jika save gagal
                            Yii::error('Unable to save display picture');
                            Yii::$app->session->setFlash('error', 'Unable to save display picture');
                        }
                    } else {
                        //error jika tidak ada folder
                        Yii::error('Unable to save display picture. Does not have a data folder');
                        Yii::$app->session->setFlash('error', 'Unable to save display picture. Does not have a data folder');
                    }
                } else {
                    // jika tidak ada pergantian pada model->profile_picture pakai attribute lama 
                    $model->profile_picture = $model->getOldAttribute('profile_picture');
                }
                if (!$model->save()) {
                    $model->validate();
                    //untuk menampilkan error di UI dengn setfLASH
                    Yii::$app->session->setFlash('error', 'Buku gagal di tambah / diubah');
                    \Yii::error("buku tidak tersimpan" . VarDumper::dumpAsString($model->errors));
                } else {
                    //untuk menampilkan successdi UI dengn setfLASH
                    Yii::$app->session->setFlash('success', 'Buku berhasil di tambah / diubah');
                    //redirect ke halaman list buku jika berhasil
                    return $this->redirect(['buku/list-buku']);
                }
            }
            return $this->render('create-update-buku', [
                'model' => $model,
                'title' => $title
            ]);
        } catch (\Exception $e) {
            var_dump($e->getMessage() . 'line:' . $e->getLine());
            Yii::error('Create/update buku failed. Detail : ' . $e->getMessage());
        }
    }
}
