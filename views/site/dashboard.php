<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-dashboard">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row row-cols-1 row-cols-md-2 g-4">
        <div class="col">
            <div class="card shadow bg-body rounded border-0">
                <div class="card-body">
                    <h5 class="card-title">Buku Tersedia</h5>
                    <p class="card-text"><?= $countBuku ?></p>
                    <a href="<?= Url::to(['buku/list-buku']) ?>" class="btn btn-primary">List Buku</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow bg-body rounded border-0">

                <div class="card-body">
                    <h5 class="card-title">Buku Dipinjam</h5>
                    <p class="card-text"><?= $countBukuDipinjam ?></p>
                    <a href="<?= Url::to(['buku/list-buku']) ?>" class="btn btn-primary">List Buku</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow bg-body rounded border-0">

                <div class="card-body">
                    <h5 class="card-title">Pinjaman Aktif</h5>
                    <p class="card-text"><?= $countModelPeminjam ?></p>
                    <a href="<?= Url::to(['peminjam/list-peminjam']) ?>" class="btn btn-primary">List Peminjam</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow bg-body rounded border-0">
                <div class="card-body">
                    <h5 class="card-title">Pengembalian</h5>
                    <p class="card-text"><?= $countModelPengembalian ?></p>
                    <a href="<?= Url::to(['pengembalian/list-pengembalian']) ?>" class="btn btn-primary">List Pengembalian</a>
                </div>
            </div>
        </div>
    </div>



</div>


<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });
</script>