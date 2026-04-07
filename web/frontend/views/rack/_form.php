<?php

use yii\bootstrap5\ActiveForm;

?>
<!--login form Modal -->
<div class="modal fade text-left" id="modalGrid" tabindex="-1" role="dialog" aria-labelledby="modalGrid" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalGridTitle"></h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'grid-form', 'action' => '']); ?>
            <div class="modal-body">
                <?= $form->field($model, 'vNama')->textInput(['required' => true]) ?>
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