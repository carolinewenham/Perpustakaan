<?php

/** @var yii\web\View $this */

use app\controllers\PeminjamController;
use app\models\KatalogBuku;
use app\models\Peminjam;
use app\models\Pengembalian;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'List Pengembalian';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- <div class="site-about"> -->

<h1><?= Html::encode($this->title) ?></h1>
<div class="p-3 mt-2 shadow p-3 mb-5 bg-body rounded table-responsive">
    <table id="table" class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Judul Buku</th>
                <th>Pengembali</th>
                <th>Tanggal Pengembalian Seharusnya</th>
                <th>Tanggal Pengembalian Sebenarnya</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model as $pengembalian) : ?>
                <tr>
                       <!-- get nama buku dari fungsi pada model katalog buku dengan id_buku sebagai parameter -->
                    <td><?= KatalogBuku::getBookName($pengembalian['id_buku']) ?></td>

                    <td>
                         <!-- get username dari fungsi pada model usern  dengan id_admin sebagai parameter -->
                        <?= User::getUsername($pengembalian['id_admin']) ?>
                    </td>
                    <td>
                        <?php
                         // get tanggal kembali sebenarnya dari fungsi pada model pengembalian dengan id_peminjam sebagai parameter 
                        $date = new DateTime(Pengembalian::getTanggalKembali($pengembalian['id_peminjam']));
                        echo $date->format('d M Y') ?>
                    </td>
                    <td>
                        <?php
                        $date = new DateTime($pengembalian['tanggal_pengembalian']);
                        echo $date->format('d M Y') ?>
                    </td>
                    <td>
                        <?php
                         // get tanggal kembali sebenarnya dari fungsi pada model pengembalian dengan id_peminjam sebagai parameter 
                        $dateEnd = Pengembalian::getTanggalKembali($pengembalian['id_peminjam']);
                        $dateEndReal = $pengembalian['tanggal_pengembalian'];
                          // get status early,ontime,late dengan pass date end dan date end real
                        $status =  Pengembalian::getStatus($dateEnd, $dateEndReal);
                        ?>
                        <!-- set button type sesuai dengan output dari getStatus -->
                        <?php if ($status == 'Early') : ?>
                            <button type="button" class="btn btn-outline-success">Early</button>
                        <?php elseif($status == 'On Time') : ?>
                            <button type="button" class="btn btn-outline-primary">On Time</button>
                        <?php else:?>
                            <button type="button" class="btn btn-outline-danger">Late</button>
                        <?php endif; ?>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });
</script>