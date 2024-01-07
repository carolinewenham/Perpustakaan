<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\SignUpForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to create katalog buku</p>

    <?php $form = ActiveForm::begin([
        'id' => 'katalog-buku',
        'layout' => 'horizontal',
    ]); ?>

    <div class="form-group">
        <?= $form->field($model, 'judul_buku')->textInput(['autofocus' => true]) ?>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'deskripsi_buku')->textInput(); ?>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'penerbit')->textInput(); ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'submit-buku']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <script>
        $('.dropdownSelect2').select2({
            minimumResultsForSearch: -1
        });
    </script>
</div>