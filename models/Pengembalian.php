<?php

namespace app\models;

use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "pengembalian".
 *
 * @property int $id
 * @property int|null $id_buku
 * @property int|null $id_peminjam
 * @property int|null $id_admin
 * @property string|null $tanggal_pengembalian
 *
 * @property User $admin
 * @property KatalogBuku $buku
 * @property Peminjam $peminjam
 */
class Pengembalian extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengembalian';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        // rules untuk validasi
        return [
            [['id_buku', 'id_peminjam','id_admin'], 'required'],
            [['id_buku', 'id_peminjam', 'id_admin'], 'integer'],
            [['tanggal_pengembalian'], 'safe'],
            [['id_buku'], 'exist', 'skipOnError' => true, 'targetClass' => KatalogBuku::class, 'targetAttribute' => ['id_buku' => 'id']],
            [['id_peminjam'], 'exist', 'skipOnError' => true, 'targetClass' => Peminjam::class, 'targetAttribute' => ['id_peminjam' => 'id']],
            [['id_admin'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_admin' => 'id']],
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
            'id_peminjam' => 'Id Peminjam',
            'id_admin' => 'Id Admin',
            'tanggal_pengembalian' => 'Tanggal Pengembalian',
        ];
    }

    /**
     * Gets query for [[Admin]].
     *
     * @return \yii\db\ActiveQuery
     *
     */

    public function getAdmin()
    {
        return $this->hasOne(User::class, ['id' => 'id_admin']);
    }
    public static function getTanggalKembali($id_peminjam)
    {
        // mengambil data peminjam dengan id tertentu
        $model = Peminjam::findOne($id_peminjam);
        //return data tanggal kembali
        return $model->tanggal_kembali;
    }
    public static function getStatus($start, $end)
    {
        // compare tanggal kembali seharusnya dan tanggal kembali sebenarnya
        $start = strtotime($start);
        $end = strtotime($end);
        if ($start < $end) {
            //buku telat dikembalikan
            return 'Late';
        } elseif ($start > $end) {
            //buku dikembalikan lebih awal
            return "Early";
        } else {
            // buku dikembalikan tepat waktu
            return "On Time";
        }
    }
    public static function updateBookStatusActive($id)
    {
        // mengambil data buku dengan id tertentu
        $model = KatalogBuku::findOne($id);
        if ($model != null) {
            //update status 1 atau tersedia
            $model->status = 1;
            if ($model->save()) {
                // Book status updated successfully
                return true;
            } else {
                \Yii::error("Status buku gagal di update" . VarDumper::dumpAsString($model->errors));
                return false;
                // Error occurred while updating book status   
            }
        }
    }
    public static function updatePeminjamStatus($id, $id_pengembalian)
    {
         // mengambil data peminjam dengan id tertentu
        $model = Peminjam::findOne($id);
        if ($model != null) {
            // update status 0 / peminjaman sudah selesai
            $model->status = 0;
            // update id pengembalian 
            $model->id_pengembalian = $id_pengembalian;
            if ($model->save()) {
                return true;
            } else {
                \Yii::error("Status peminjam gagal di update" . VarDumper::dumpAsString($model->errors));
                return false;
            }
        }
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
     * Gets query for [[Peminjam]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeminjam()
    {
        return $this->hasOne(Peminjam::class, ['id' => 'id_peminjam']);
    }
}
