<?php

/** @var yii\web\View $this */

use app\models\KatalogBuku;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'List Buku';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- <div class="site-about"> -->
<div class="row">
    <div class="col-sm-10">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-sm-2">
        <a href="<?= Url::to(['buku/create-update-buku']) ?>"><button class="btn btn-primary float-end"> Add Buku</button></a>
    </div>
</div>
<div class=" p-3 mt-2 shadow p-3 mb-5 bg-body rounded table-responsive">
    <table id="table" class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Judul Buku</th>
                <th>Status </th>
                <th>Penerbit</th>
                <th>Deskripsi</th>
                <th>Created By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model as $buku) : ?>
                <tr>
                    <td><?= $buku['judul_buku'] ?></td>
                    <td>
                        <!-- const yang ada pada katalog buku -->
                        <?php if ($buku['status'] == KatalogBuku::TERSEDIA) : ?>
                            <?= KatalogBuku::TERSEDIA_TEXT ?>
                        <?php else : ?>
                            <?= KatalogBuku::DIPINJAM_TEXT ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $buku['penerbit'] ?></td>
                    <td><?= $buku['deskripsi_buku'] ?></td>
                    <!-- get username dari fungsi yang ada pada model user dengan parameter created by -->
                    <td><?= User::getUsername($buku['created_by']) ?></td>
                    <td>
                        <a href="<?= Url::to(['buku/create-update-buku', 'id' => $buku['id']]) ?>"><button class="btn btn-success"> Edit Buku</button></a>
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
</script>