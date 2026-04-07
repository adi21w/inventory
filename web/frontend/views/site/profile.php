<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use common\helper\Helper;

$this->title = 'My Profile';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile("@web/js/apps/component/password.js?v" . date('Ymd'), ['depends' => [\yii\web\YiiAsset::class]]);
?>
<div class="row">
    <!-- Profile Card -->
    <div class="col-md-4">
        <div class="card text-center mb-4">
            <div class="card-body">
                <?= Html::img('@web/images/profile.webp', ['alt' => 'Profile', 'class' => 'rounded-circle mb-3', 'width' => 100]); ?>
                <h4><?= $model->name; ?></h4>
                <p class="text-muted"><?= Helper::showRole($model->id); ?></p>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" id="btn-change-password">Change Password</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <!-- Form -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Profile</div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(['id' => 'user-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <?= $form->errorSummary($model); ?>
                <div class="mb-3">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'readonly' => true])->label('Full Name') ?>
                </div>
                <div class="mb-3">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'readonly' => ($model->email) ? true : false])->label('Email') ?>
                </div>
                <div class="mb-3">
                    <?= $form->field($model, 'number_phone')->textInput(['maxlength' => true, 'readonly' => ($model->number_phone) ? true : false])->label('Phone') ?>
                </div>
                <div class="mb-3">
                    <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>