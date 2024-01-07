<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\SignUpForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Sign Up';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to sign up</p>

    <?php $form = ActiveForm::begin([
        'id' => 'sign-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'password_repeat')->passwordInput() ?>




    <div class="form-group">
            <?= Html::submitButton('Sign Up', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <script>
        $('.dropdownSelect2').select2({
            minimumResultsForSearch: -1
        });
        $('#signupform-date').datepicker({
            format: 'dd/mm/yyyy',
        });
    </script>
</div>