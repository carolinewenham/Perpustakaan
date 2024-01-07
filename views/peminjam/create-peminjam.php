<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\SignUpForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Add Peminjam';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to create katalog buku</p>

    <?php $form = ActiveForm::begin([
        'id' => 'katalog-buku',
        'layout' => 'horizontal',
           
    ]); ?>
    <!-- field form dibawah ini menggunakan yii:active form  -->
    <?= $form->field($model, 'id_buku')->dropdownList($listBuku, ['class' => 'dropdownSelect2 form-control'])->label('Buku'); ?>
    <?= $form->field($model, 'id_admin')->dropdownList($listAdmin, ['class' => 'dropdownSelect2 form-control'])->label('Peminjam'); ?>
    <?= $form->field($model, 'tanggal_pinjam')->textInput(['value' => $now, 'disabled' => true]) ?>
    <?= $form->field($model, 'tanggal_kembali')->textInput(['value' => $end, 'disabled' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'submit-buku']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <script>

        // initialize select2 library
        $('.dropdownSelect2').select2({
            minimumResultsForSearch: -1
        });
    </script>
</div>