<?php

/** @var yii\web\View $this */

use app\models\KatalogBuku;
use app\models\Peminjam;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'List Peminjam';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- <div class="site-about"> -->

<div class="row">
    <div class="col-sm-10">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-sm-2">
        <a href="<?= Url::to(['peminjam/create-peminjam']) ?>"><button class="btn btn-primary float-end"> Add Peminjam</button></a>
    </div>
</div>
<div class="p-3 mt-2 shadow p-3 mb-5 bg-body rounded table-responsive">
    <table id="table" class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Judul Buku</th>
                <th>Peminjam </th>
                <th>Status</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Pengembalian</th>
                <th>Due In </th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model as $peminjam) : ?>
                <tr>
                    <!-- get nama buku dari fungsi pada model katalog buku dengan id_buku sebagai parameter -->
                    <td><?= KatalogBuku::getBookName($peminjam['id_buku']) ?></td>
                      <!-- get username dari fungsi pada model usern  dengan id_admin sebagai parameter -->
                    <td><?= User::getUsername($peminjam['id_admin']) ?></td>
                    <td>
                          <!-- memanggil const yang ada pada model peminjam -->
                        <?php if ($peminjam['status'] == Peminjam::PINJAMAN_AKTIF) : ?>
                            <?= Peminjam::PINJAMAN_AKTIF_TEXT ?>
                        <?php else : ?>
                            <?= Peminjam::PINJAMAN_SELESAI_TEXT ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                        $date = new DateTime($peminjam['tanggal_pinjam']);
                        echo $date->format('d M Y') ?>
                    </td>
                    <td>
                        <?php
                        $date = new DateTime($peminjam['tanggal_kembali']);
                        echo $date->format('d M Y') ?>
                    </td>
                    <td>
                       <!-- memanggil fungsi countdaydiff yang ada pada model peminjam -->
                        <?= Peminjam::countDayDiff($peminjam['tanggal_pinjam'], $peminjam['tanggal_kembali']) ?>
                    </td>
                    <td>
                        <button class="btn btn-primary" onclick="createPengembalian('<?= $peminjam['id_buku'] ?>','<?= $peminjam['id'] ?>','<?= $peminjam['id_admin'] ?>')"> Kembalikan Buku </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>



<!-- </div> -->


<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });

    function createPengembalian(id_buku, id_peminjam, id_admin) {
        // pass post request to create pengembalian action 
        // pass id_buku,id_peminjam,id_admin
        $.post('<?= Url::to(['/pengembalian/create-pengembalian']) ?>', {
            id_buku,
            id_peminjam,
            id_admin
        }, function(result) {
            if (result) {
                console.log(result);
                window.location.reload();
            }
        });
    }
</script>