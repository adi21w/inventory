<?php

use yii\bootstrap5\ActiveForm;
use yii\web\View;
// use yii\helpers\Html;
// use kartik\select2\Select2;
// use yii\web\JsExpression;
// use yii\helpers\Url;
// use yii\helpers\ArrayHelper;

$this->registerJsFile("@web/js/template-s2.js?v" . date('Ymd'), ['position' => View::POS_HEAD]);
?>
<!--login form Modal -->
<div class="modal fade text-left" id="modalGrid" tabindex="-1" role="dialog" aria-labelledby="modalGrid" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalGridTitle"></h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'grid-form', 'action' => '']); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'username')->textInput(['required' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'name')->textInput(['required' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'number_phone')->textInput(['maxlength' => true, 'data-format' => 'number']) ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" class="btn btn-blue ml-1" id="btn-grid-action" data-action data-id>
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Save</span>
                </button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>