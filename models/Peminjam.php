<?php

namespace app\models;

use Yii;
use yii\helpers\VarDumper;


/**
 * This is the model class for table "peminjam".
 *
 * @property int $id
 * @property int|null $id_buku
 * @property int|null $id_admin
 * @property int|null $status
 * @property string|null $tanggal_pinjam
 * @property string|null $tanggal_kembali
 *
 * @property User $admin
 * @property KatalogBuku $buku
 * @property Pengembalian[] $pengembalians
 */
class Peminjam extends \yii\db\ActiveRecord
{

    //declare constan
    const PINJAMAN_AKTIF = 1;
    const PINJAMAN_SELESAI = 0;
    const PINJAMAN_AKTIF_TEXT = 'Pinjaman Aktif';
    const PINJAMAN_SELESAI_TEXT = 'Pinajaman Selesai';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'peminjam';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
         //rules untuk validasi pada tabel peminjam

        return [
            [['id_buku', 'id_admin','tanggal_pinjam','tanggal_kembali'], 'required'],
            [['id_buku', 'id_admin', 'status'], 'integer'],
            [['tanggal_pinjam', 'tanggal_kembali'], 'safe'],
            [['id_buku'], 'exist', 'skipOnError' => true, 'targetClass' => KatalogBuku::class, 'targetAttribute' => ['id_buku' => 'id']],
            [['id_admin'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_admin' => 'id']],
            [['id_pengembalian'], 'exist', 'skipOnError' => true, 'targetClass' => Pengembalian::class, 'targetAttribute' => ['id_pengembalian' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_buku' => 'Id Buku',
            'id_admin' => 'Id Admin',
            'id_pengembalian' => 'Id Pengembalian',
            'status' => 'Status',
            'tanggal_pinjam' => 'Tanggal Pinjam',
            'tanggal_kembali' => 'Tanggal Kembali',
        ];
    }

    /**
     * Gets query for [[Admin]].
     *
     * @return \yii\db\ActiveQuery
     */
    public static function updateBookStatus($id)
    {
        // fungsi untuk update status buku menjadi 0 atau tidak tesedia 
        // mencari data buku dengan id tertentu
        $model = KatalogBuku::findOne($id);

        if ($model != null) {
            //set status jadi 0 / tidak tesedia 
            $model->status = 0;
            if ($model->save()) {
                // status berhasil
                return true;
            } else {
                // status gagal di updat
                \Yii::error("Status buku gagal di update" . VarDumper::dumpAsString($model->errors));
                return false;
            }
        }
    }
   
    public static  function countDayDiff($start, $end)
    {
        // untuk menghitung selisih tanggal ( due in)
        $startConverted = strtotime($start);
        $endConverted = strtotime($end);
        $daysDifference = floor(($endConverted - $startConverted) / (60 * 60 * 24));// kalkulasi total detik dalam 1 hari
        return $daysDifference . ' Hari';
    }
    public function getAdmin()
    {
        return $this->hasOne(User::class, ['id' => 'id_admin']);
    }

    /**
     * Gets query for [[Buku]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBuku()
    {
        return $this->hasOne(KatalogBuku::class, ['id' => 'id_buku']);
    }


    /**
     * Gets query for [[Pengembalians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengembalians()
    {
        return $this->hasMany(Pengembalian::class, ['id_peminjam' => 'id']);
    }
}
