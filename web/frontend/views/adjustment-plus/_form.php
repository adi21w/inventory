<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;
use common\helper\Helper;
use kartik\date\DatePicker;
use common\models\Warehouses;

$this->registerJsFile("@web/js/template-s2.js?v1.0.1", ['position' => View::POS_HEAD]);
$this->registerJsFile("@web/js/wbraganca-init.js?v1.0.1", ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile("@web/js/apps/adjustment/form-plus.js?v" . date('Ymd'), ['depends' => [\yii\web\YiiAsset::class]]);
?>
<div class="component-page-form" data-route-parent="/adjustment-plus">
    <?php $form = ActiveForm::begin(['id' => 'dynamic-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->errorSummary($model); ?>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'iGudangId')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Warehouses::find()->where(['eDeleted' => 'Tidak'])->all(), 'iId', 'vNama'),
                        'options' => ['placeholder' => 'Search..', 'required' => false],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'eKategori')->dropDownList(
                        ['STOCK OPNAME' => 'STOCK OPNAME', 'DITEMUKAN' => 'DITEMUKAN', 'PEMBETULAN BATCH' => 'PEMBETULAN BATCH', 'SALDO AWAL' => 'SALDO AWAL']
                    );
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'tKeterangan')->textarea(['rows' => 5, 'required' => true]) ?>
                </div>
            </div>
        </div>
    </div>
    <?= $form->errorSummary($modelDetails); ?>
    <div class="card mb-4">
        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
            'widgetBody' => '.container-items', // required: css class selector
            'widgetItem' => '.item', // required: css class
            'limit' => 15, // the maximum times, an element can be cloned (default 999)
            'min' => 1, // 0 or 1 (default 1)
            'insertButton' => '.add-item', // css class
            'deleteButton' => '.remove-item', // css class
            'model' => $modelDetails[0],
            'formId' => 'dynamic-form',
            'formFields' => [
                'iId',
                'iProdukId',
                'iKemasanId',
                'vBatch',
                'dExpired',
                'iQty'
            ],
        ]); ?>
        <div class="card-body container-items">
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="float-end add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Add Item</button>
                </div>
            </div>
            <div class="row align-items-end">
                <div class="col-sm-12">
                    <?php foreach ($modelDetails as $index => $modelDetail) : ?>
                        <div class="item panel panel-default">
                            <div class="panel-body" style="padding:10px;">
                                <div class="row">
                                    <div class="col-md-1">
                                        <div style="margin-top:2.3rem;">
                                            <span class="place-number"><?= $index + 1 ?></span>
                                        </div>
                                        <?= (!$modelDetail->isNewRecord) ? Html::activeHiddenInput($modelDetail, "[{$index}]iId") : null; ?>
                                    </div>
                                    <div class="col-sm-3">
                                        <?= $form->field($modelDetail, "[{$index}]iProdukId")->widget(Select2::classname(), [
                                            'initValueText' => (isset($modelDetail->product)) ? $modelDetail->product->vNama : '',
                                            'options' => ['placeholder' => 'Search Product', 'required' => true, 'data-input' => 'produk'],
                                            'pluginOptions' => [
                                                'allowClear' => false,
                                                'minimumInputLength' => 3,
                                                'language' => [
                                                    'errorLoading' => new JsExpression("formatError"),
                                                ],
                                                'ajax' => [
                                                    'url' => Url::to(['/products/get-list']),
                                                    'dataType' => 'json',
                                                    'data' => new JsExpression('formatData'),
                                                    'processResults' => new JsExpression('resultJStemp'),
                                                    'cache' => true
                                                ],
                                                'escapeMarkup' => new JsExpression('formatMarkup'),
                                                'templateResult' => new JsExpression('formatRepoTemp3'),
                                                'templateSelection' => new JsExpression('formatRepoSelectionTemp'),
                                            ],
                                        ]);
                                        ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?= $form->field($modelDetail, "[{$index}]iKemasanId")->dropDownList(
                                            ($model->isNewRecord) ? [] : [$modelDetail->product?->kemasan?->iId => $modelDetail->product?->kemasan?->vNama, $modelDetail->product?->kemasankecil?->iId => $modelDetail->product?->kemasankecil?->vNama],
                                            ['required' => true, 'data-input' => 'kemasan']
                                        );
                                        ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?= $form->field($modelDetail, "[{$index}]vBatch")->textInput(['data-input' => 'batch']) ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?= $form->field($modelDetail, "[{$index}]dExpired")->widget(DatePicker::classname(), [
                                            'options' => ['placeholder' => 'Pilih Tanggal ...'],
                                            'pluginOptions' => [
                                                'autoclose' => true,
                                                'format' => 'yyyy-mm-dd', // Sesuaikan format DB
                                                'todayHighlight' => true
                                            ]
                                        ]); ?>
                                    </div>
                                    <div class="col-md-1">
                                        <?= $form->field($modelDetail, "[{$index}]iQty")->textInput(['required' => true, 'data-format' => 'number', 'maxlength' => 4, 'data-input' => 'qty']) ?>
                                    </div>
                                    <div class="col-sm-1">
                                        <label class="control-label" style="width: 100%">&nbsp;</label>
                                        <button type="button" class="float-end remove-item btn btn-danger btn-xs">
                                            <i class="fas fa-minus-square"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php DynamicFormWidget::end(); ?>
        <div class="card-footer">
            <div class="col-md-12">
                <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-warning']) ?>
                <?= ($model->eConfirm != 'Ya') ? Html::submitButton('Save', ['class' => ($model->isNewRecord) ? 'btn btn-blue' : 'btn btn-success', 'value' => 'save', 'name' => 'buttonExec']) : null ?>
                <?php if (!$model->isNewRecord && $model->eConfirm == 'Tidak') { ?>
                    <?= Html::submitButton('Confirmation', ['class' => ($model->isNewRecord) ? 'btn btn-purple' : 'btn btn-blue', 'value' => 'confirm', 'name' => 'buttonExec', 'data-button-confirm' => true]) ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>