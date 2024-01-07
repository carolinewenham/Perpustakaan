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
use app\models\User;
use app\models\SignUpForm;
use yii\helpers\VarDumper;

class PeminjamController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            //untuk autentikasi action list peminjam dan create peminjam buku hanya dapat diakses oleh user yang login
            'access' => [
                'class' => AccessControl::class,
                'only' => ['list-peminjam','create-peminjam'],
                'rules' => [
                    [
                        'actions' => ['list-peminjam','create-peminjam'],
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
    public function actionListPeminjam()
    {
        $model = Peminjam::find()->where(['status' => 1])->all();
         //diatas untuk mengambil semua data yang ada dalam model peminjam 
        //find()-> adalah metode yang disediakan oleh Yii2 ActiveRecord untuk membuat objek kueri untuk tabel peminjam.
        //all-> untuk mengambil semua record yang ada di tabel peminjam dengan status 1 atau pinjaman aktif
          //render view list buku dengan pass model agar dapat ditampilkan di view
        return $this->render('list-peminjam', [
            'model' => $model
        ]);
    }
    public function actionCreatePeminjam()
    {
        // add new record pada tabel peminjam
        $model = new Peminjam();
        //akses function pada model katalog buku untuk mendapat
        //list buku dengan status 1 / tersedia
        $listBuku = KatalogBuku::getBook();
         //akses function pada model user untuk mendapat semua user
        $listAdmin = User::getUser();
        //mendapat tanggal hari ini dengan format tahun - bulan - tanggal
        $now = date('Y-m-d');
        // convert strtotime 
        $convertedDate = strtotime($now);
        //diatambah 7 hari untuk mendapat end date 
        $end =  strtotime("+7 day", $convertedDate);
        // convert format end date dengan format tahun-bulan-tanggal
        $convertedDateEnd = date('Y-m-d', $end);
        try {
            //cek apakah req post
            if ($model->load(Yii::$app->request->post())) {
                //set status 1 artinya peminjam aktif
                $model->status = 1;
                // set tanggal pinjam dengan tanggal hari ini
                $model->tanggal_pinjam = $now;
                // set tanggal kembali dengan tanggal hari ini + 7 hari
                $model->tanggal_kembali = $convertedDateEnd;
                if (!$model->save()) {
                    $model->validate();
                    // menampilkan error pada UI
                    Yii::$app->session->setFlash('error', 'Peminjam gagal ditambakan');
                    \Yii::error("Data Pinjaman gagal di simpan" . VarDumper::dumpAsString($model->errors));
                } else {
                    if ($model->id_buku != null) {
                        // update status buku menjadi dipinjam saat data peminjam berhawsil di save
                        if (Peminjam::updateBookStatus($model->id_buku)) {
                            // menampilkan message success pada UI
                            Yii::$app->session->setFlash('success', 'Peminjam berhasil ditambakan');
                            return $this->redirect(['peminjam/list-peminjam']);
                        }
                    }
                }
            }
            // render view crete-peminjam
            return $this->render('create-peminjam', [
                'model' => $model,
                'listBuku' => $listBuku,
                'listAdmin' => $listAdmin,
                'now' => $now,
                'end' => $convertedDateEnd,
            ]);
        } catch (\Exception $e) {
            var_dump($e->getMessage() . 'line:' . $e->getLine());
            Yii::error('Create peminjam failed. Detail : ' . $e->getMessage());
        }
    }
}
