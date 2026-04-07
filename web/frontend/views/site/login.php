<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model common\models\LoginForm */

$this->title = 'Sistem Inventory - Login';
$this->registerCssFile('@web/css/pages/auth.css', [
    'depends' => [\yii\web\YiiAsset::class],
]);
?>
<div id="auth">
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="#"><?= Html::img('@web/images/logo.webp', ['alt' => 'Logo']); ?></a>
                </div>
                <h1 class="auth-title text-primary">Log in</h1>
                <p class="auth-subtitle mb-5">Sistem Inventory</p>

                <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => true]); ?>
                <?= $form->field($model, 'username', [
                    'options' => ['class' => 'form-group position-relative has-icon-left mb-4'],
                    'inputTemplate' => "{input}<div class='form-control-icon'><i class='bi bi-person'></i></div>"
                ])
                    ->textInput(['class' => 'form-control form-control-xl', 'placeholder' => 'Username', 'maxlength' => 10])
                    ->label(false)
                ?>
                <?= $form->field($model, 'password', [
                    'options' => ['class' => 'form-group position-relative has-icon-left mb-4'],
                    'inputTemplate' => "{input}<div class='form-control-icon'><i class='bi bi-shield-lock'></i></div>"
                ])
                    ->textInput(['class' => 'form-control form-control-xl', 'placeholder' => 'Password', 'type' => 'password', 'maxlength' => 20])
                    ->label(false)
                ?>
                <?= $form->field($model, 'rememberMe', [
                    'template' => "<div class=\"form-check form-check-lg d-flex align-items-end\">{input}\n{label}\n{error}</div>",
                ])->checkbox([
                    'class' => 'form-check-input me-2',
                    'id' => 'flexCheckDefault',
                ], false) ?>
                <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">
            </div>
        </div>
    </div>

</div>