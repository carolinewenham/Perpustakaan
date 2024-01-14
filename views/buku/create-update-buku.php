<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\SignUpForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to <?=$title?></p>

    <?php $form = ActiveForm::begin([
        'id' => 'katalog-buku',
        'layout' => 'horizontal',
    ]); ?>
    // cek jika title == 'Update Buku'
    <?php if ($title == 'Update Buku') : ?>
        <?php if ($model->profile_picture) : ?>
            <?php
            $baseUrl = Url::base(true);
            $imageUrl = $baseUrl . '/data/' . $model->profile_picture;
            ?>
    // view image 
            <img src="<?= Html::encode($imageUrl) ?>" alt="Uploaded Image" style="max-width: 200px;min-width:200px;">
        <?php endif; ?>
    <?php endif; ?>

    <div class="form-group mt-2">
        <?= $form->field($model, 'judul_buku')->textInput(['autofocus' => true]) ?>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'deskripsi_buku')->textInput(); ?>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'penerbit')->textInput(); ?>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'profile_picture')->fileInput()->hint('Recommended size 400 x 400 pixels')->label('Cover') ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'fix_picture')->textInput(['type' => 'text',  "style" => "display:none"])->label(false) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'submit-buku']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <script>
        // function on change
        $('#katalogbuku-profile_picture').change(function() {
        
            var fileInput = document.getElementById('katalogbuku-profile_picture');
            // jika terdapat length berarti tidak di cancel
            if (fileInput.files.length > 0) {
                // ambil filename
                var filename = fileInput.files[0].name;
                //dimasukan dalam value fix_picture
                $('#katalogbuku-fix_picture').val(filename);
            } else {
                // jika tidak ada value dikosongkan 
                $('#katalogbuku-fix_picture').val('');
            }
        })

        $('.dropdownSelect2').select2({
            minimumResultsForSearch: -1
        });
    </script>
</div>
