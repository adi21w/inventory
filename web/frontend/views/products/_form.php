<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
use common\models\Packs;
use common\models\Rack;
// use yii\helpers\Url;
// use yii\web\JsExpression;
// use kartik\file\FileInput;
// use kartik\date\DatePicker;

$this->registerJsFile("@web/js/template-s2.js?v" . date('Ymd'), ['position' => View::POS_HEAD]);
$this->registerJsFile("@web/js/apps/produk/form.js?v" . date('Ymd'), ['position' => View::POS_HEAD]);
?>
<div class="card" data-route-parent='/products/index'>
    <div class="card-body">
        <div class="produk-form" data-route-parent="/produk">
            <?php $form = ActiveForm::begin(['id' => 'produk-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
            <?= $form->errorSummary($model); ?>
            <?= (!$model->isNewRecord) ? $form->field($model, 'iId')->hiddenInput([])->label(false) : null ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-md-8">
                            <?= $form->field($model, 'vNama')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-sm-4">
                            <?= $form->field($model, 'iRak')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(Rack::findAll(['eDeleted' => 'Tidak']), 'iId', 'vNama'),
                                'options' => ['placeholder' => 'Rak'],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]);
                            ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, "dPrice")->widget(MaskedInput::className(), [
                                'options' => [],
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'digits' => 2,
                                    'text-Align' => 'left',
                                    'digitsOptional' => true,
                                    //'radixPoint' => ',',
                                    'groupSeparator' => '.',
                                    'autoGroup' => true,
                                    'removeMaskOnSubmit' => true,
                                ],
                            ]) ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, "dMargin")->widget(MaskedInput::className(), [
                                'options' => [],
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'digits' => 2,
                                    'text-Align' => 'left',
                                    'digitsOptional' => true,
                                    //'radixPoint' => ',',
                                    'groupSeparator' => '.',
                                    'autoGroup' => true,
                                    'removeMaskOnSubmit' => true,
                                ],
                            ]) ?>
                        </div>
                        <div class="col-sm-12">
                            <?= $form->field($model, 'vDeskripsi')->textarea(['rows' => 4]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'iKemasanBesarId')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(Packs::findAll(['eDeleted' => 'Tidak']), 'iId', 'vNama'),
                                'options' => ['placeholder' => 'Kemasan Besar'],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]);
                            ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'iIsiKemasanBesar')->textInput(['type' => 'number']) ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'iKemasanKecilId')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(Packs::findAll(['eDeleted' => 'Tidak']), 'iId', 'vNama'),
                                'options' => ['placeholder' => 'Kemasan Kecil'],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]);
                            ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'iIsiKemasanKecil')->textInput(['type' => 'number']) ?>
                        </div>
                        <div class="col-md-12">
                            <?php if ($model->vImage) { ?>
                                <?= Html::img('@web/uploads/products/' . $model->vImage, ['alt' => 'Product Image', 'class' => 'img-thumbnail']) ?>
                            <?php } ?>
                            <?= $form->field($model, 'vImage')->fileInput(['class' => 'with-validation-filepond-produk', 'data-max-file-size' => '30KB'])->label('Upload Images') ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'vSlug')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="float-start">
                        <?= Html::a('<i class="fas fa-long-arrow-alt-left me-1"></i>Kembali', ['index'], ['class' => 'btn btn-warning']) ?>
                        <?= Html::submitButton('<i class="fas fa-save me-1"></i></i>Simpan', ['class' => 'btn btn-blue']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>
    const isNewRecord = <?= ($model->isNewRecord) ? 1 : 0 ?>;
</script>