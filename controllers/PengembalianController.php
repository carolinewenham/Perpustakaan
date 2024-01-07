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
use app\models\User;
use app\models\SignUpForm;
use yii\helpers\VarDumper;

class PengembalianController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                  //untuk autentikasi action list pengembalian dan create pengambalian buku hanya dapat diakses oleh user yang login
                'only' => ['list-pengembalian','create-pengembalian'],
                'rules' => [
                    [
                        'actions' => ['list-pengembalian','create-pengembalian'],
                        'allow' => true,
                        'roles' => ['@'],// all log in user
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
    public function actionListPengembalian()
    {
        $model = Pengembalian::find()->all();
          //diatas untuk mengambil semua data yang ada dalam model pengembalian
        //find()-> adalah metode yang disediakan oleh Yii2 ActiveRecord untuk membuat objek kueri untuk tabel pengembalian.
        //all-> untuk mengambil semua record yang ada di tabel pengembalian
          //render view list pengembalian dengan pass model agar dapat ditampilkan di view
        return $this->render('list-pengembalian', [
            'model' => $model
        ]);
    }
    public function actionCreatePengembalian()
    {

        try {
            // id_buku, peminjam, admin didapat dari data AJAX yang
            // dikirimkan ke action ini
            $id_buku = Yii::$app->request->post("id_buku");
            $id_peminjam = Yii::$app->request->post("id_peminjam");
            $id_admin = Yii::$app->request->post("id_admin");

            // create new record pada tabel pengembalian
            $model = new Pengembalian();
            //set id buku sesuai dengan variabel id buku diatas
            $model->id_buku = $id_buku;
            //set id peminjam sesuai dengan variabel id peminjam diatas
            $model->id_peminjam = $id_peminjam;
            //set id admin sesuai dengan variabel id admin diatas
            $model->id_admin = $id_admin;
            //set tanggal pengembalian -> tanggal hari data dimasukkan
            $model->tanggal_pengembalian = date('Y-m-d');


            if (!$model->save()) {
                $model->validate();
                //set Yii::error 
                \Yii::error("Data Pinjaman gagal di simpan" . VarDumper::dumpAsString($model->errors));
                return false;
            } else {
            
                // cek jika model id buku dan id pinjaman tidak null
                if ($model->id_buku != null && $model->id_peminjam != null) {
                 // masukan id buku ke fungsi pada model pengembalian untuk update status buku 
                 //masukkan id pinjaman dan id pengembalian ke fungsi pada model pengembalian
                 // untuk update status pinjaman dan set id pengembalian pada tabel peminjam
                    if (Pengembalian::updateBookStatusActive($model->id_buku) && Pengembalian::updatePeminjamStatus($model->id_peminjam,$model->id)) {
                        //send succes messange ke UI
                        Yii::$app->session->setFlash('success', 'Pengembalian berhasil ditambahkan');
                        return true;
                    } else {
                        //send error messange ke UI
                        Yii::$app->session->setFlash('error', 'Pengembalian gagal ditambahkan');
                        return false;
                    }
                }
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage() . 'line:' . $e->getLine());
            Yii::error('Create pengembalian failed. Detail : ' . $e->getMessage());
        }
    }
}
