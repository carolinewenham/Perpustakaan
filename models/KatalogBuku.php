<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "katalog_buku".
 *
 * @property int $id
 * @property string|null $judul_buku
 * @property int|null $status
 * @property string|null $penerbit
 * @property int|null $created_by
 * @property string|null $deskripsi_buku
 *
 * @property User $createdBy
 * @property Peminjam[] $peminjams
 * @property Pengembalian[] $pengembalians
 */
class KatalogBuku extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    const TERSEDIA = 1;
    const DIPINJAM = 0;
    const TERSEDIA_TEXT = 'Tersedia';
    const DIPINJAM_TEXT = 'Dipinjam';
    public static function tableName()
    {
        return 'katalog_buku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        // untuk peraturan pada setiap field yang ada pada katalog buku 
        // digunakan untuk validasi 
        return [
            [['judul_buku', 'penerbit'], 'required'],
            [['status', 'created_by'], 'integer'],
            [['deskripsi_buku'], 'string'],
            [['judul_buku', 'penerbit'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'judul_buku' => 'Judul Buku',
            'status' => 'Status',
            'penerbit' => 'Penerbit',
            'created_by' => 'Created By',
            'deskripsi_buku' => 'Deskripsi Buku',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public static function getBook()
    {
        // mengambil semua buku dengan status 1 / tersedia 
        $models = KatalogBuku::find()->where(['status' => 1])->all();
        $result = [];
        // memasukan ke dalam array result dengan id dan judul saja 
        foreach ($models as $model) {
            $result[$model->id] = $model->judul_buku;
        }
        return $result;
    }
    public static function getBookName($id)
    {
        // mengambil data buku dengan id tertentu
        $model = KatalogBuku::findOne($id);
        // lalu diambil data judul buku
        return $model->judul_buku;
    }
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Peminjams]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeminjams()
    {
        return $this->hasMany(Peminjam::class, ['id_buku' => 'id']);
    }

    /**
     * Gets query for [[Pengembalians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengembalians()
    {
        return $this->hasMany(Pengembalian::class, ['id_buku' => 'id']);
    }
}
